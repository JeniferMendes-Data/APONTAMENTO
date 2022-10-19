//função para carregar os scripts iniciais da tela view/gerenciar.php
function funIniciaTimeGrid(sup) {
	var varApontTime = document.getElementById('divApontTime');
	var varTimeGrid = new FullCalendar.Calendar(varApontTime, {
		themeSystem: 'bootstrap5',
		initialView: 'timeGridDay', 
		initialDate: new Date(), //só exibe o dia selecionado
		nowIndicator: true, //indica a hora atual 
		editable: false, //proibe edição do horario do evento
		selectable: true, //permite seleção de linha
		dayMaxEvents: true,
		slotEventOverlap: false, //não permite eventos sobrepostos visualmente
		allDaySlot: false, //não exibe opção de evento de "dia inteiro"
		contentHeight: 'auto', //não permite scroll no TimeGrid
		expandRows: true, //ajusta a altura da linha ao texto
		headerToolbar: {		
			left:'',			
			right: 'timeGridDay,listDay'
		},
		eventOrder: document.getElementById("selAcao").value == 2?"title":"start",
		loading: function (parStatus) {
			if (parStatus) {
				$('#divCarregando').show();
			}
		},
		eventClick: function(evento) {
			document.getElementById("selAcao").value == 2?interna_selecionaAprovacao(evento):interna_editApont(evento, sup);
		}
	});
	varTimeGrid.setOption('locale', 'pt-br');
	varTimeGrid.render();	
	global_calendario = varTimeGrid;
}

//função para pesquisar apontamentos na tela de gerenciar
function js_pesquisaGerenciar(dataPesquisa, login, acao, sup) {
	url = '/functions/json.php';

	document.getElementById("divSecaoInd").innerHTML = '';
	$("#aprovExecutar").off();//remove evento do botão

	if (sup == true && (jsonNomes !== undefined || jsonNomes == "")) {
		stringLogin = jsonNomes.map(function(element){return "'" + element['LOGIN'] + "'";}).join();
	}else{
		stringLogin = "'" + login + "'";
	}

	//carrega os dados
	return $.ajax({
		url : url,
		data : {"recuperaApontamento" :
					{  "stringUsuario": stringLogin,
						"data": dataPesquisa,
						"login": login
					}
				},
		type : "post",
		success : function(data) {
			if (data == "null") {
				var eventos = global_calendario.getEvents();
				if (eventos.length > 0) {					
					eventos.forEach(element => {
						element.remove(); //deleta eventos anteriores
					});	
				};
				$(document).ready(function(){
					bootbox.alert({
						buttons: {
							ok: {
								label: 'OK',
								className: 'bg text-light'
							},
						},
						centerVertical: true,
						title: "Apontamento Inexistente",
						message: "Não foi possível encontrar lançamentos para o período selecionado!",
					})
				});
			} else {				
				jsonApontamentos = JSON.parse(data);
				if(acao==2){
					interna_botaoAprova();
				}else{
					interna_atualizaContagem(jsonApontamentos, dataPesquisa, sup); //atualiza o indicador
				}
				interna_atualizaEventos(jsonApontamentos, dataPesquisa, acao, login); //atualiza o calendario
			}
			$('#divCarregando').hide(); //oculta gif de carregamento no calendário
		}
	});
}

//função para carregar colaboradores subordinados do supervisor
function js_recuperaNomeIDSup(id_sup) {
	url = '/functions/json.php';
	//carrega os dados
	return $.ajax({
		url : url,
		data : {"recuperaSecaoNomeSup" :
					{  "usuarioLogado": id_sup.value
					}
				},
		type : "post",
		success : function(data) {
			jsonNomes = JSON.parse(data);
			if (jsonNomes.length > 0) {
				//limpa as opções anteriores e add a opção Todos
				$("#" + id_sup.id).empty()
				$("#" + id_sup.id).append("<option value='TODOS'>TODOS</option>");
				for (var i = 0; i < jsonNomes.length; i++) { //alimenta o campo do select de seção na tela
					$("#" + id_sup.id).append("<option value='" + jsonNomes[i]['LOGIN'] + "'>" + jsonNomes[i]['NOME'] + "</option>");					
				}
				$("#" + id_sup.id).selectpicker('refresh');
				$("#" + id_sup.id).off("shown.bs.select"); //remove o evento de click para não carregar os valores novamente no botão
				//carrega o TimeGrid a primeira vez na tela para o supervisor
				js_pesquisaGerenciar(new Date().toLocaleDateString('en-ZA'), id_sup.value,1, 1);
			}
		}
	});
}

//função para carregar apontamentos no calendário
function interna_atualizaEventos(json, data, acao, login) {	
	var varDisplayTimeGrid = 'none',varCondicao='', varClassName='', varBackgroundColor='', varBorderColor='';
	$('#divCarregando').show(); //exibe gif de carregamento no calendário
	
	var eventos = global_calendario.getEvents();
	if (eventos.length > 0) {					
		eventos.forEach(element => {
			element.remove(); //deleta eventos anteriores
		});	
	};

	//reseta variaveis de linhas selecionadas para aprovação
	global_selectApvRpv = [];
	var apt = [];

	if (acao==2) { //aprovar
		global_calendario.changeView('listDay');
		global_calendario.setOption('eventOrder','title'); //ordena pelo nome
		varClassName='aprov';
		if (login == 'TODOS') {
			varCondicao='element["VALIDA"] == "P"';			
		}else{
			varCondicao='(element["VALIDA"] == "P") && (element["ID_USUARIO"] == login)';
		}
	} else if(login == 'TODOS') { //filtro de supervisor
		global_calendario.changeView('listDay');
		varBackgroundColor='(element["VALIDA"]=="A")?"#03BB85":(element["VALIDA"]=="R")?"#BE2444":"#0D6EFD"';
		varBorderColor='(element["VALIDA"]=="A")?"#03BB85":(element["VALIDA"]=="R")?"#BE2444":"#0D6EFD"';		
		varCondicao='1==1';
	}else{
		varDisplayTimeGrid = '';
		varBackgroundColor='(element["VALIDA"]=="A")?"#03BB85":(element["VALIDA"]=="R")?"#BE2444":"#0D6EFD"';
		varBorderColor='(element["VALIDA"]=="A")?"#03BB85":(element["VALIDA"]=="R")?"#BE2444":"#0D6EFD"';
		varCondicao='element["ID_USUARIO"] == login';
	}
	document.getElementsByClassName('fc-timeGridDay-button')[0].style.display = varDisplayTimeGrid;
	json.forEach(element => {
		if (eval(varCondicao)) {
			apt.push({
					id: element["ID_APONTAMENTO"],
					title: element["ID_USUARIO"] + " - OS: " + element["N_OS"] + " - PARTE/PEÇA: " + element["PARTE_DESC"] + " - ATIV: " + element["ATIV_DESC"] + " - RETRABALHO: " + element["RETRABALHO"] + " - SERVIÇO DE CAMPO: " + element["SERV_CAMPO"], 
					start: new Date(element["H_INICIO"].date).toJSON(),
					end: new Date(element["H_FIM"].date).toJSON(),
				extendedProps: {
					OS: element["N_OS"],
					PARTE: element["PARTE"],					
					PARTEDESC: element["PARTE_DESC"],
					ATIVIDADE: element["ATIVIDADE"],
					ATIVIDADEDESC: element["ATIV_DESC"],
					RETRABALHO: element["RETRABALHO"],
					SERV_CAMPO: element["SERV_CAMPO"],
					STATUS: element["VALIDA"],
					NOME: element["NOME"],
					H_INICIO: element["H_INICIO"],
					H_FIM: element["H_FIM"],
					SECAO: element["SECAO_APONT"],
					OBS: element["OBS"],
					ID_USUARIO: element["ID_USUARIO"],
					CHECK: false
				},
				className: varClassName,				
				backgroundColor: eval(varBackgroundColor),
				borderColor: eval(varBorderColor)			
			});	
		}			
	});

	global_calendario.gotoDate(new Date(data)); //altera a data do calendário para a mesma dos eventos
	global_calendario.addEventSource(apt); //adiciona os eventos no calendário
	global_calendario.render(); //renderiza o calendário com os novos dados
}

//função para carregar o indicador de apontamentos
function interna_atualizaContagem(json, data, sup) {
	var objSecao = {}, objColab = {}, itemS = "", itemC = "", arrSecao, arrColab, sumHoras, dblClick="";

	json.forEach(element => { //alimenta um objeto com a seção e cada colaborador que realizou apontamento para a data filtrada
		var secaoAtual = element["PSECAO_DESCRICAO"];
		var nomeAtual = element['NOME'];
		var hInicio = element['H_INICIO'];
		var hFim = element['H_FIM'];
		var valida = element['VALIDA'];
		if (!objSecao[secaoAtual]) { //verifica se a seção não existe no array
			Object.assign(objSecao, {[secaoAtual]:[nomeAtual]}); //inclui colaborador na secao
			Object.assign(objColab, {[nomeAtual]:[[hInicio, hFim, valida]]}) //inclui horas e status no array de colaborador
			return;
		}else if (objSecao[secaoAtual].find(elNA => elNA == nomeAtual)){//verifica se o nome existe no array da seção			
			objColab[nomeAtual].push([hInicio, hFim, valida]); //inclui horas e status no array de colaborador			
		}else{
			objSecao[secaoAtual].push(nomeAtual); //inclui colaborador na secao
			Object.assign(objColab, {[nomeAtual]:[[hInicio, hFim, valida]]}); //inclui horas e status no array de colaborador
		}
	});
	
	arrSecao = Object.keys(objSecao);
	arrColab = Object.keys(objColab);
	if (sup) {//carrega todos os calaboradores subordinados
		jsonNomes.forEach(function(eleNome){
			if(!arrSecao.includes(eleNome["SECAO_DESCRICAO"])) {
				arrSecao.push(eleNome["SECAO_DESCRICAO"]);
			};
			arrColab.includes(eleNome['NOME'])?"":arrColab.push(eleNome['NOME']); //completa a lista de nomes com colaboradores que não realizaram apontamento na data
		});		
	}	
	document.getElementById("divSecaoInd").innerHTML = ""; //limpa a div para recarregar a lista de seções
	$("#aprovExecutar").off();//remove evento do botão
	
	arrColab.sort();
	arrSecao.sort();
	arrSecao.forEach(el => { //carrega collapse de seção
		itemC = "";		
		var idSecao = el.split(" ").join("").toLocaleLowerCase();
		//remove caracteres especiais da string
		idSecao = idSecao.replace(/[^a-zA-Z 0-9]+/g,'');
		itemS = '<div class="accordion-item"><h2 class="accordion-header" id = "tit' + idSecao + '"><button class="accordion-button collapsed bg text-light" type="button" data-bs-toggle="collapse" data-bs-target="#col' + idSecao + '" aria-expanded="false" aria-controls="col' + idSecao + '">'+ el + '</button></h2><div id="col' + idSecao + '" class="accordion-collapse collapse" aria-labelledby="tit' + idSecao + '" data-bs-parent="#divSecaoInd">';		
		arrColab.forEach(elC => { //carrega colaboradores da secao
			if(sup){
				login = jsonNomes.filter(function (elNL){if (elNL["NOME"] == elC){return elNL;}});
				if (login[0] == undefined || login[0]['SECAO_DESCRICAO'] !== el) {
					return;
				}
				dblClick = 'ondblclick="js_alteraFiltroNome(\''+ login[0]["LOGIN"] +'\')"';
			};
			itemC += '<div class="row align-items-center" '+ dblClick +'><div class="accordion-body col-md-4">'+ elC +':</div>';//cria o nome na lista
			sumHoras = interna_somaHoras(objColab[elC]);
			itemC += '<div class="col-md-6"><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="'+ sumHoras[0][1] +'" aria-valuemin="0" aria-valuemax="100" style="background-color: #03BB85; width: '+ sumHoras[0][1] +'%"></div><div class="progress-bar" role="progressbar" aria-valuenow="'+ sumHoras[0][2] +'" aria-valuemin="0" aria-valuemax="100" style="background-color: #BE2444; width: '+ sumHoras[0][2] +'%"></div><div class="progress-bar" role="progressbar" aria-valuenow="'+ sumHoras[0][3] +'" aria-valuemin="0" aria-valuemax="100" style="width: '+ sumHoras[0][3] +'%"></div></div></div><div class="col-md-2">'+ sumHoras[1] +'</div></div>';		
		});
		//inclui div de colaboradores na string da collapse da seção;
		itemS += itemC + '</div></div>';
		document.getElementById("divSecaoInd").innerHTML += itemS; //adiciona a collapse de seção completa na tela
	});
}

//função para somar horas apontadas pelo(s) colaborador(es) no dia
function interna_somaHoras(objColab) {
	var dtHIni, dtHFim,result = 0,  resultMin = 0,  resultA = 0, resultP = 0, resultR = 0, totalHra, totalPerc;
	if (objColab == undefined) {
		totalPerc = [[0],[0],[0],[0]];
		totalHra = 0;
	} else {
		objColab.forEach(element => {
			dtHIni = new Date(element[0].date);
			dtHFim = new Date(element[1].date);
			result = ((dtHFim.getHours() - dtHIni.getHours())*60) + (dtHFim.getMinutes() - dtHIni.getMinutes());
			switch (element[2]) { //calcula percentual conforme o status
				case "A":
					resultA += result;
					resultMin += result;
					break;
				case "R":
					resultR += result;
					break;
				default:
					resultP += result;
					resultMin += result;
					break;
			}
		});
		totalHra = Math.floor(resultMin/ 60) + ":" + (resultMin % 60); //soma de horas trabalhadas
		totalPerc = [[parseFloat(((resultMin/480)*100).toFixed(2))], //percentual total --Usa 8hrs como base
					[resultA>0?(resultA/480)*100:0], //percentual de aprovados
					[resultR>0?(resultR/480)*100:0], //percentual de reprovados
					[resultP>0?(resultP/480)*100:0]] //percentual de pendentes
	}		
	return [totalPerc, totalHra];
}
//função para alterar a option selecionada no filtro de nome
function js_alteraFiltroNome(login) {
	$('#selNome').selectpicker('val', login);
	$('#selNome').selectpicker('refresh');

	js_pesquisaGerenciar(document.getElementById('inpDataFiltro').value.split('/').reverse().join('/'), document.getElementById('selNome').value, document.getElementById('selAcao').value, false); //chama função para atualizar o grid de apontamentos
}

//função para montar o modal de edição e aprovação de apontamento
function interna_editApont(dadosApont, sup) {
	var hoje = new Date(), ontem = new Date();
	var readOnly = true, required = false, aprov = false, enviarEdit = false, msgRetorno = "";
	var botaoFechar = document.getElementById("btnFechar");
	var botaoEditar = document.getElementById("btnEditar");
	var botaoAprovar = document.getElementById("btnAprovar");
	var aprovarDisplay = 'none', editarDisplay = 'none', fecharInnerHTML = 'Fechar', selParte, selAtiv,selParteDesc, selAtivDesc, selCausaRetrabalho;

	ontem.setDate(hoje.getDate() -1);

	var modal = document.getElementById('divEditAprov');
	var divModal = bootstrap.Modal.getOrCreateInstance(modal);		
	var campos = {	 //elementos do modal a serem preenchidos	
		lblEditAprov: document.querySelector("#lblEditAprov"),
		inpDataApt: document.querySelector("#inpDataInicio"),
		inpHoraInicio: document.querySelector("#inpHoraInicio") ,
		inpHoraFim: document.querySelector("#inpHoraFim"),
		inpServCampo: document.querySelector("#inpServCampo"),
		inpNumOS: document.querySelector("#inpNumOS"),
		inpSecao: document.querySelector("#inpSecao"),
		inpObs: document.querySelector("#inpObs")	
	};
	//carrega opções dos campos de select
	//js_recuperaDadosParteAtiv(document.querySelector("#selParte"))	

	//carrega valor nos campos
	campos.lblEditAprov.innerHTML = !dadosApont.event.extendedProps.NOME?"Não informado":dadosApont.event.extendedProps.NOME; //nome do colaborador no título
	campos.inpNumOS.value = !dadosApont.event.extendedProps.OS?"Não informado":dadosApont.event.extendedProps.OS.substring(dadosApont.event.extendedProps.OS.length -6);
	campos.inpDataApt.value = new Date(dadosApont.event.extendedProps.H_INICIO.date).toLocaleDateString('en-GB');
	campos.inpHoraInicio.value = new Date(dadosApont.event.extendedProps.H_INICIO.date).toLocaleTimeString();
	campos.inpHoraFim.value = new Date(dadosApont.event.extendedProps.H_FIM.date).toLocaleTimeString();
	selParte = !dadosApont.event.extendedProps.PARTE?"Não informado":dadosApont.event.extendedProps.PARTE;
	selAtiv = !dadosApont.event.extendedProps.ATIVIDADE?"Não informado":dadosApont.event.extendedProps.ATIVIDADE;
	selParteDesc = !dadosApont.event.extendedProps.PARTEDESC?"Não informado":dadosApont.event.extendedProps.PARTEDESC;
	selAtivDesc = !dadosApont.event.extendedProps.ATIVIDADEDESC?"Não informado":dadosApont.event.extendedProps.ATIVIDADEDESC;
	selCausaRetrabalho = !dadosApont.event.extendedProps.RETRABALHO?"Não informado":dadosApont.event.extendedProps.RETRABALHO;
	campos.inpServCampo.value = !dadosApont.event.extendedProps.SERV_CAMPO?"N":dadosApont.event.extendedProps.SERV_CAMPO;	
	campos.inpServCampo.checked = campos.inpServCampo.value == "S"?true:false;
	campos.inpSecao.value = !dadosApont.event.extendedProps.SECAO?"Não informado":dadosApont.event.extendedProps.SECAO;		
	campos.inpObs.value = !dadosApont.event.extendedProps.SECAO?"":dadosApont.event.extendedProps.OBS;		
	


	if (!dadosApont.event.extendedProps.SECAO) {//permite edicao apenas em apontamentos que possuem o campo SECAO_APONT na tabela preenchidos
		readOnly = true;
		required = false;
		aprovarDisplay = 'none';
		editarDisplay = 'none';
		fecharInnerHTML = 'Fechar';
	} else if (dadosApont.event.extendedProps.STATUS && dadosApont.event.extendedProps.STATUS == 'P') { //apontamento pendente permite edição		
		if (sup && campos.inpDataApt.value <= ontem.toLocaleDateString('en-GB')) {//habilita botão de edicao e aprovação
			readOnly = false;
			required = true;
			aprovarDisplay= 'block';
			editarDisplay = 'block';
			fecharInnerHTML = 'Cancelar';
		}else if (!sup && campos.inpDataApt.value == hoje.toLocaleDateString('en-GB')){ //habilita edicao do colaborador
			readOnly = false;
			required = true;
			aprovarDisplay = 'none';
			editarDisplay = 'block';
			fecharInnerHTML = 'Cancelar';
		}else{//Fora do período permitido para edição
			readOnly = true;
			required = false;
			aprovarDisplay = 'none';
			editarDisplay = 'block';
			fecharInnerHTML = 'Fechar';
		}
	}else{//habilita somente consulta
		readOnly = true;
		required = false;
		aprovarDisplay = 'none';
		editarDisplay = 'none';
		fecharInnerHTML = 'Fechar';
	}
	

	divModal.show();

	$("#selParte, #selAtiv, #selCausaRetrabalho").selectpicker();//inicia os selects

	var camposEdit = document.querySelectorAll(".EDIT");

	camposEdit.forEach(ele => {
		if (ele.type == "select-one" || ele.type == 'checkbox' || ele.type == 'button') {
			ele.disabled = readOnly;				
		}else{
			ele.readOnly = readOnly;
		}
		ele.required = required;
	});

	botaoAprovar.style.display = aprovarDisplay;
	botaoEditar.style.display = editarDisplay;
	botaoFechar.innerHTML = fecharInnerHTML;
	var retorno;
	$('#' + botaoEditar.id).on('click', function (element) {
		if (document.getElementById("inpDataInicio").value == "") {
			bootbox.alert({
				buttons: {
					ok: {
						label: 'OK',
						className: 'bg text-light'
					},
				},
				centerVertical: true,
				title: "Erro ao validar horas",
				onEscape: false,
				message: "Favor cancelar a edição e abrir novamente"
			});
		}else{
			//valida horas antes de editar
			retorno = js_ApontarValidaHora(sup, dadosApont.event.extendedProps.ID_USUARIO, 'ger', dadosApont.event.id);
			if (retorno == undefined) {
				bootbox.alert({
					buttons: {
						ok: {
							label: 'OK',
							className: 'bg text-light'
						},
					},
					centerVertical: true,
					title: "Erro ao validar horas",
					onEscape: false,
					message: "Favor cancelar a edição e abrir novamente"
				});
			}else{
				var retornoJSON = JSON.parse(retorno.responseText);	
				if (retornoJSON && retornoJSON[0]["TOTAL"] == 0) {
					js_enviaEditApont(botaoEditar, dadosApont.event.id, dadosApont.event.extendedProps.ID_USUARIO, sup);	
				}
			}				
		}	
		
	});
	$('#' + botaoAprovar.id).on('click', function (element) {	
		//valida horas antes de editar
		retorno = js_ApontarValidaHora(sup, dadosApont.event.extendedProps.ID_USUARIO, 'ger', dadosApont.event.id);
		var retornoJSON = JSON.parse(retorno.responseText);	
		if (retornoJSON && retornoJSON[0]["TOTAL"] == 0) {
			js_enviaEditApont(botaoAprovar, dadosApont.event.id, dadosApont.event.extendedProps.ID_USUARIO, sup);
		}
	});

//refatorar
	if (editarDisplay !== 'none') {
		interna_addEventosIniciaisEdit();		
		//seleciona a opção do banco de dados
		// $('#selParte').on('refreshed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
		// 	$("#selParte").selectpicker('val', selParte);			
		// 	js_recuperaDadosParteAtiv(document.getElementById("selAtiv"), selParte);//carrega as opções do select			
		// });
		// $('#selAtiv').on('refreshed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
		// 	$("#selAtiv").selectpicker('val', selAtiv);
		// });

		$("#selParte option").remove();//remove nomes anteriores
		$("#selParte").append("<option value='"+selParte+"'>"+selParteDesc+"</option>");				
		$("#selParte").selectpicker('refresh');
		$('#selParte').selectpicker('val', selParte);
		$("#selAtiv option").remove();//remove nomes anteriores
		$("#selAtiv").append("<option value='"+selAtiv+"'>"+selAtivDesc+"</option>");				
		$("#selAtiv").selectpicker('refresh');
		$('#selAtiv').selectpicker('val', selAtiv);
		//js_recuperaDadosParteAtiv(document.getElementById("selParte"), ""); //carrega as opções do select

		$("#selCausaRetrabalho").selectpicker('val', selCausaRetrabalho);				
		$("#selCausaRetrabalho").selectpicker('refresh');
		
	}else{
		$("#selParte option").remove();//remove nomes anteriores
		$("#selParte").append("<option value='"+selParte+"'>"+selParteDesc+"</option>");				
		$("#selParte").selectpicker('refresh');
		$('#selParte').selectpicker('val', selParte);
		$("#selAtiv option").remove();//remove nomes anteriores
		$("#selAtiv").append("<option value='"+selAtiv+"'>"+selAtivDesc+"</option>");				
		$("#selAtiv").selectpicker('refresh');
		$('#selAtiv').selectpicker('val', selAtiv);
		$("#selCausaRetrabalho").selectpicker('val', selCausaRetrabalho);				
		$("#selCausaRetrabalho").selectpicker('refresh');
	}
//refatorar

	$('#divEditAprov').one('hidden.bs.modal', function (event) {//limpa os valores dos campos do modal
		$("#selParte, #selAtiv, #selCausaRetrabalho").selectpicker('val', '');	
		$('#selAtiv, #selParte, #selCausaRetrabalho').selectpicker('destroy');
		$("#btnAprovar, #btnEditar, #inpNumOS, #selParte, #selAtiv, #selCausaRetrabalho").off(); //remove os eventos dos campos
		
		var camposEdit = document.querySelectorAll(".EDIT");

		camposEdit.forEach(ele => {
			ele.value = "";
		});
		if (divModal._element !== null) {
			divModal.dispose();//limpa o valor dos campos ao ocultar
		}		
	  })

}
//Função para selecionar os apontamentos no grid
function interna_selecionaAprovacao(evento) {

	evento.el.classList.toggle('select'); //alterna a cor a linha
	evento.event.setExtendedProp('CHECK', !evento.event.extendedProps.CHECK); //alterna o estado do check
}

//Função para aprovar/reprovar os apontamentos selecionados na tela
function interna_executaAprovacao(acao) {
	var eventos = global_calendario.getEvents();
	var aptSel = [];
	var modAprov, msgRetorno = "Sucesso";
	acao = acao.value == 'APV'?'Aprovar': 'Reprovar';
	eventos.forEach(element => {
		if (element.extendedProps.CHECK == true) {
			aptSel.push(element.id);
		}
	});

	if (aptSel.length > 0) {
		bootbox.confirm({
			buttons: {
				confirm: {
					label: 'Confirmar',
					className: 'bg text-light'
				},
				cancel: {
					label: 'Cancelar'
				}
			},
			centerVertical: true,
			title: "Aprovar",
			message: "Deseja "+ acao +" " + aptSel.length + " lançamento(s)?",
			callback: function (result) {	
				if (result) {//se confirmou a ação
					var modalProcessando = bootbox.dialog({ 
						message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Processando apontamentos... 0 de ' + aptSel.length + '</div>', 
						closeButton: false,
						size: 'large',
						onEscape: false,
						centerVertical: true 
					}); 
					modalProcessando.modal('show');//exibe "processando" na tela 

					url = '/functions/json.php';
					var totalExecutado = 0; 

					aptSel.forEach(element => {//executa no banco todos os apontamentos selecionados						
						return $.ajax({
							url : url,
							data : {"aprovaApontamento" :
										{  "id": element,
											"acao": acao == "Aprovar"?"A":"R"
										}
									},
							type : "post",
							success : function(data) {
								totalExecutado ++; //atualiza contador
								modalProcessando.find('.bootbox-body').html('<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Processando apontamentos... ' + totalExecutado + ' de ' + aptSel.length + '</div>');
								// if(data !== "true"){
								// 	msgRetorno = "Erro " + element;
								// };
								//refatorar - está retornando o XML também
								if (totalExecutado == aptSel.length) {
									modalProcessando.modal('hide'); //oculta "processando" da tela									
									interna_exibeResultado();
								}								
							}
						});
					});
				}
			}
		});
		function interna_exibeResultado(){ 
			bootbox.alert({
				buttons: {
					ok: {
						label: 'OK',
						className: 'bg text-light'
					},
				},
				centerVertical: true,
				title: "Ação Concluída",
				onEscape: false,
				message: msgRetorno,
				callback: function () {
					js_pesquisaGerenciar(document.getElementById("inpDataFiltro").value.split('/').reverse().join('/'), document.getElementById('selNome').value, document.getElementById('selAcao').value, 1);
				}
			});
		}
	}else{
		bootbox.alert({
			buttons: {
				ok: {
					label: 'OK',
					className: 'bg text-light'
				},
			},
			centerVertical: true,
			title: "Apontamento",
			message: "Nenhum apontamento selecionado para aprovar!",
		})
	}
}

//Função para incluir os botões de aprovação na tela view->gerenciar.php
function interna_botaoAprova() {
	document.getElementById("divSecaoInd").innerHTML = '<div class="mt-2"><div class="form-check form-check-inline"><input class="form-check-input aprovar" type="radio" name="radioExecutar" id="radAprov" value="APV" checked><label class="form-check-label" for="radAprov">Aprovar</label></div><div class="form-check form-check-inline"><input class="form-check-input reprovar" type="radio" name="radioExecutar" id="radReprov" value="REP"><label class="form-check-label" for="radReprov">Reprovar</label></div><button type="button" name="aprovExecutar" id="aprovExecutar" class="btn bg text-light">Executar</button>';	
	$("#aprovExecutar").on( "click", function() {
		interna_executaAprovacao(document.querySelector('input[name="radioExecutar"]:checked')); 
	  });
	
}

function interna_addEventosIniciaisEdit(){
	//preencher campos da OS
	$('#inpNumOS').on('change', function (element) {
		js_tamanhoCampoOS(document.getElementById("inpNumOS"), "ger");
		js_recuperaDadosOS(document.getElementById("inpNumOS"), "ger");
	});	

	//retornar as partes e atividades
	$('#selParte, #selAtiv').on('shown.bs.select', function (e, clickedIndex, isSelected, previousValue) {
		js_recuperaDadosParteAtiv(this, $("#selParte").val());
	});
	$('#selParte').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) { 
		$("#selAtiv option").remove();
		$("#selAtiv").selectpicker("refresh");
	});
}

//função para enviar o modal de edicao
function js_enviaEditApont(botao, idApont, login, sup){
	var url = '/functions/json.php', acao, valores={}, camposEnviar; 
	var data = new Date();
	document.getElementById(botao.id).disabled = true; //bloqueia o botão para não solicitar a edição mais de uma vez
	
	camposEnviar = document.querySelectorAll(".ENVIAR");
	camposEnviar.forEach(ele => {
		if (ele.type == "checkbox") {
			ele.value = ele.checked == true?"S":"N";
		}
		Object.assign(valores, {[ele.id]:ele.value});
	});
	if (botao.id == "btnAprovar" && document.getElementById("inpDataInicio").value < data.toLocaleDateString()) {
		acao = "E";
		Object.assign(valores, {"VALIDA":"A"}); //aprovar
	}else if (botao.id == "btnAprovar") {
		acao = "A";
		Object.assign(valores, {"VALIDA":"A"}); //aprovar
	}else{
		acao = "E";
		Object.assign(valores, {"VALIDA":"M"}); //manter o status atual
	}

	return $.ajax({
		url : url,
		data : {"aprovaApontamento" :
					{  "id": idApont,
						"acao": acao,
						"campos":valores
					}
				},
		type : "post",
		async: false,
		complete: function(data) {
			$("#divEditAprov").modal('hide'); 
			document.getElementById(botao.id).disabled = false;	
			js_pesquisaGerenciar(document.getElementById('inpDataFiltro').value.split('/').reverse().join('/'), document.getElementById('selNome').value,1, sup);						
		}
	});	
}