
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
		loading: function (status) {
			if (status) {
				$('#divCarregando').show();
			}
		},
		eventClick: function(evento) {
			//document.getElementsByClassName('fc-timeGridDay-button')[0].style.display = ''
			document.getElementById("selAcao").value == 2?console.log(evento.event.id):funIniciaEditAprov(evento, sup)
		}
	});
	varTimeGrid.setOption('locale', 'pt-br');
	varTimeGrid.render();	
	global_calendario = varTimeGrid;
}

//função para pesquisar apontamentos na tela de gerenciar
function js_pesquisaGerenciar(dataPesquisa, login, acao, sup) {
	url = '/functions/json.php';

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
						"data": dataPesquisa
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
					interna_aprova();
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

	var apt = [];
	if (acao==2) { //aprovar
		global_calendario.changeView('listDay');
		varClassName='aprov';
		if (login == 'TODOS') {
			varCondicao='element["VALIDA"] == "P"';			
		}else{
			varCondicao='(element["VALIDA"] == "P") && (element["ID_USUARIO"] == login)';
		}
	} else if(login == 'TODOS') { //filtro de supervisor
		global_calendario.changeView('listDay');
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
					title: "RESPONSÁVEL: " + element["ID_USUARIO"] + " - OS: " + element["N_OS"] + " - PARTE/PEÇA: " + element["PARTE"] + " - ATIV: " + element["ATIVIDADE"] + " - RETRABALHO: " + element["RETRABALHO"] + " - SERVIÇO DE CAMPO: " + element["SERV_CAMPO"], 
					start: new Date(element["H_INICIO"].date).toJSON(),
					end: new Date(element["H_FIM"].date).toJSON(),
				extendedProps: {
					OS: element["N_OS"],
					PARTE: element["PARTE"],
					ATIVIDADE: element["ATIVIDADE"],
					RETRABALHO: element["RETRABALHO"],
					SERV_CAMPO: element["SERV_CAMPO"],
					STATUS: element["VALIDA"],
					NOME: element["NOME"],
					H_INICIO: element["H_INICIO"],
					H_FIM: element["H_FIM"],
					SECAO: element["SECAO_APONT"]
				},
				className: varClassName,				
				backgroundColor: varBackgroundColor,
				borderColor: varBorderColor			
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
	arrColab.sort();
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
function funIniciaEditAprov(dadosApont, sup) {
	var hoje = new Date();
	var divModal = new bootstrap.Modal(document.getElementById('divEditAprov'));		
	var campos = {	 //elementos do modal a serem preenchidos	
		lblEditAprov: document.querySelector("#lblEditAprov"),
		inpDataApt: document.querySelector("#inpDataApt"),
		inpHraIni: document.querySelector("#inpHraIni") ,
		inpHraFim: document.querySelector("#inpHraFim"),
		selParte: document.querySelector("#selParte"),
		selAtiv: document.querySelector("#selAtiv"),
		selCausaRetrabalho: document.querySelector("#selCausaRetrabalho"),
		inpServCampo: document.querySelector("#inpServCampo"),
		secao: document.querySelector("#inpSecao"),
		inpNumOS: document.querySelector("#inpNumOS"),
		inpSecao: document.querySelector("#inpSecao")		
	};
	//carrega opções dos campos de select
	//js_recuperaDadosParteAtiv(document.querySelector("#selParte"))	

	//carrega valor nos campos
	campos.lblEditAprov.innerHTML = !dadosApont.event.extendedProps.NOME?"Não informado":dadosApont.event.extendedProps.NOME; //nome do colaborador no título
	campos.inpNumOS.value = !dadosApont.event.extendedProps.OS?"Não informado":dadosApont.event.extendedProps.OS;
	campos.inpDataApt.value = new Date(dadosApont.event.extendedProps.H_INICIO.date).toLocaleDateString();
	campos.inpHraIni.value = new Date(dadosApont.event.extendedProps.H_INICIO.date).toLocaleTimeString();
	campos.inpHraFim.value = new Date(dadosApont.event.extendedProps.H_FIM.date).toLocaleTimeString();
	campos.selParte.value = !dadosApont.event.extendedProps.PARTE?"Não informado":dadosApont.event.extendedProps.PARTE;
	campos.selAtiv.value = !dadosApont.event.extendedProps.ATIVIDADE?"Não informado":dadosApont.event.extendedProps.ATIVIDADE;
	campos.selCausaRetrabalho.value = !dadosApont.event.extendedProps.RETRABALHO?"Não informado":dadosApont.event.extendedProps.RETRABALHO;
	campos.inpServCampo.value = !dadosApont.event.extendedProps.SERV_CAMPO?"Não informado":dadosApont.event.extendedProps.SERV_CAMPO;	
	campos.inpSecao.value = !dadosApont.event.extendedProps.SECAO?"Não informado":dadosApont.event.extendedProps.SECAO;	

	if (campos.inpDataApt.value == hoje.toLocaleDateString() || sup) {
		
	}

	if (!dadosApont.event.extendedProps.SECAO) {
		bootbox.alert({
			buttons: {
				ok: {
					label: 'OK',
					className: 'bg text-light'
				},
			},
			centerVertical: true,
			title: "Edição",
			message: "Erro ao recuperar a seção do apontamento. Não será possível editar!",
		})
	} else if (!dadosApont.event.extendedProps.STATUS && dadosApont.event.extendedProps.STATUS == 'P') { //apontamento pendente permite edição		
		if (sup) {//habilita botão de aprovação
			
		}
	}else{//apontamento aprovado ou reprovado
		
	}
	

	divModal.show();
}

//Função para incluir os botões de aprovação na tela view->gerenciar.php
function interna_aprova() {
	document.getElementById("divSecaoInd").innerHTML = '<div class="mt-2"><input type="radio" class="btn-check" name="aprov" id="aprovar" autocomplete="off" checked><label class="btn btn-outline-success" for="aprovar">Aprovar</label><input type="radio" class="btn-check" name="aprov" id="reprovar" autocomplete="off"><label class="btn btn-outline-danger" for="reprovar">Reprovar</label></div>';

}