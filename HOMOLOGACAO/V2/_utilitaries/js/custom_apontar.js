//Função para verificar hora fim do lançamento e lançamento com D+1 e D-1
function js_ApontarValidaHora(sup, usuLogin = "", origem = "", id = ""){
	var url = '/functions/json.php';
	var inpHoraInicio = document.getElementById("inpHoraInicio");
	var inpHoraFim = document.getElementById("inpHoraFim");
	var inpDataInicio = document.getElementById("inpDataInicio");
	var inpDataFim = document.getElementById("inpDataFim");

	if (inpHoraInicio.value != "" && inpHoraFim.value != "") { //verifica se já finalizou o preenchimento
		// var dataInformada = new Date();
		// var dataAlterada = new Date();
		// var inpDataFim = document.getElementById("inpDataFim");

		//permite lançamento depois da meia-noite
		// if (inpHoraInicio.value >= "14:30" && inpHoraFim.value<= "08:00") {
		// 	//verifica se o lançamento é para o dia seguinte ou dia anterior
		// 	if (sup == 1 && dataInformada.value != '') { //se for supervisor se baseia na hora lançada e não na hora atual
		// 		var dataSplit = inpDataInicio.value.split('/');
		// 		dataInformada = new Date(dataSplit[2], dataSplit[1], dataSplit[0]); //o lançamento é calculado pela data de início
		// 		dataInformada.setHours(inpHoraInicio.value.substring(0,2));
		// 		dataInformada.setMinutes(inpHoraInicio.value.substring(3,5));
		// 	}

		// 	if (dataInformada.getHours() >= "14" && dataInformada.getMinutes() >= "30") {
		// 		dataAlterada.setDate(dataInformada.getDate() + 1);
		// 		inpDataInicio.value = dataInformada.toLocaleDateString();
		// 		inpDataFim.value = dataAlterada.toLocaleDateString();
		// 	}else if(dataInformada.getHours() <= "08"){
		// 		dataAlterada.setDate(dataInformada.getDate() - 1);
		// 		inpDataInicio.value = dataAlterada.toLocaleDateString();
		// 		inpDataFim.value = dataInformada.toLocaleDateString();
		// 	}else{
		// 		inpHoraFim.value = "";
		// 		$(document).ready(function(){
		// 			$(document.body)[0].innerHTML += '<div class="modal fade" id="modalValidaHora" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title text-danger" id="TituloModalCentralizado">Lançamento Inválido</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"><span aria-hidden="true"></span></button></div><div class="modal-body">Lançamento retroativo não é permitido!</div><div class="modal-footer"><button type="button" class="btn btn-secondary bg" data-bs-dismiss="modal">Fechar</button></div></div></div></div>';
		// 			$("#modalValidaHora").modal("toggle");
		// 		});
		// 	}
		// }else 
		if(inpHoraFim.value <= inpHoraInicio.value){ //não permite lançamento com hora fim menor que hora início
			inpHoraFim.value = "";
			$(document).ready(function(){
				bootbox.alert({
					buttons: {
				        ok: {
				            label: 'OK',
				            className: 'bg text-light'
				        },
					},
					centerVertical: true,
				    title: "Apontamento Inválido",
				    message: "Hora fim deve ser maior que hora início!",
				})
			});
		}else if(origem == ""){ //lançamento primeiro turno
			if (inpDataInicio.value == '') {
				inpDataInicio.value = new Date().toLocaleDateString('en-GB');
			}
			inpDataFim.value = inpDataInicio.value;
		}
		var jsonDtHrIni = new Date(inpDataInicio.value.split('/').reverse().join('/') + " " + inpHoraInicio.value);
		var jsonDtHrFim = new Date(inpDataInicio.value.split('/').reverse().join('/') + " " + inpHoraFim.value);

		if (jsonSecaoNomeSup !== undefined) {
			var usu = jsonSecaoNomeSup.find(element => element['NOME'] == document.getElementById("selNome").value)
		}

		//verifica se existe apontamento para o período
		return $.ajax({
			url : url,
			data : {"checaIntervalo" :
						{  "hraIni": jsonDtHrIni.toLocaleString(),
							"hraFim": jsonDtHrFim.toLocaleString(),
							"login": usu==undefined?usuLogin:usu['LOGIN'],
							"id": id
						}
					},
			type : "post",
			async: false,
			success : function(data) {
				var retorno = JSON.parse(data);	
				if (retorno && retorno[0]["TOTAL"] !== 0) {
					inpHoraFim.value = "";
					inpHoraInicio.value = "";
					inpDataInicio.value = "";
					if (inpDataFim) {
						inpDataFim.value = "";						
					}
					bootbox.alert({
						buttons: {
							ok: {
								label: 'OK',
								className: 'bg text-light'
							},
						},
						centerVertical: true,
						title: "Apontamento Inválido",
						message: "Já existe apontamento realizado para o intervalo informado!",
					})	
					return false;
				}				
			}
		});
		
	}
}

//função para carregar os dados da OS nos campos caso alguma OS genérica seja selecionada
function js_apontarSelecionaItem(id, descricao, filial, nomeCentroCusto, origem = ""){
	if (origem == "") {
		document.getElementById("inpOSDesc").value = descricao;
		document.getElementById("inpFilial").value = filial;
		document.getElementById("inpCentroCusto").value = nomeCentroCusto;	
		$("#selParte option").remove();
		$("#selParte").selectpicker("refresh");
		$("#selAtiv option").remove();
		$("#selAtiv").selectpicker("refresh");		
	}
	
	document.getElementById("inpNumOS").value = id;	
}

//função que valida o valor do campo de OS
function js_tamanhoCampoOS(campo, origem = ""){
	if (origem == "") {
		document.getElementById("inpOSDesc").value = "";
		document.getElementById("inpFilial").value = "";
		document.getElementById("inpCentroCusto").value = "";		
	}
	var valorCampo = campo.value;
	if (valorCampo.length == 5) {
		document.getElementById(campo.id).value = 0 + valorCampo;
	}else if (valorCampo.length !== 6) {
		campo.value = "";
		bootbox.alert({
			buttons: {
		        ok: {
		            label: 'OK',
		            className: 'bg text-light'
		        },
			},
			centerVertical: true,
		    title: "Apontamento Inválido",
		    message: "Número de OS inválido!",
		});
	}
}

//função para preencher os campos da OS conforme click no botão de pesquisa da OS
function js_recuperaDadosOS(campoOS, origem = ""){
	url = '/functions/json.php';
	if (campoOS.value.length == 6) {
		return $.ajax({
			url : url,
			data : {"recuperaDadosOS" :
						{  "numOS": campoOS.value
						}
					},
			type : "post",
			success : function(data) {
				var retorno = JSON.parse(data);				
				if (retorno == null) {
					campoOS.value = "";
					bootbox.alert({
						buttons: {
					        ok: {
					            label: 'OK',
					            className: 'bg text-light'
					        },
						},
						centerVertical: true,
					    title: "OS não encontrada",
					    message: "Verifique o número digitado!",
					});
				}else if(origem == "" && retorno.length > 0){
					document.getElementById("inpOSDesc").value = retorno[0]["TMOVCOMPL_DESCRICAOCOMP"];
					document.getElementById("inpFilial").value = retorno[0]["GFILIAL_CIDADE"];
					document.getElementById("inpCentroCusto").value = retorno[0]["GCCUSTO_NOME"];					
				}else if(origem == "ger" && retorno.length > 0){
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
						title: "Editar",
						message: "Deseja Alterar para a OS " + campoOS.value + ": " + retorno[0]["TMOVCOMPL_DESCRICAOCOMP"],
						callback: function (acao) {
							if (!acao) {
								campoOS.value = campoOS.defaultValue;
							}
						}
					});
				}
			}
		});
	}
}

//função para preencher os campos de Parte/Peça e atividade conforme a seção
function js_recuperaDadosParteAtiv(campoOrigem, campoFiltro){
	url = '/functions/json.php';

	var numOS = document.getElementById("inpNumOS"), OSParada = "";
	//verifica o intervalo de OS's de parada
	var retorno = $.ajax({
		url: url,
		type: "post",
		async: false,
		data : {"listaOSGenerica":""},
		complete: function (data) {
			OSParada = JSON.parse(data.responseText);
			OSParada = OSParada.split(",");
		}
	})
	
	if (OSParada !== "" && OSParada.length > 0) {	
		var retornoEvery = OSParada.find(ele => ele.replaceAll("'","").substring(3) == numOS.value);
		if (numOS.value !== "" && retornoEvery !== undefined) {//preenche com parada
			//exclui options anteriores do botão
			$("#" + campoOrigem.id + " option").remove();
			$("#" + campoOrigem.id).selectpicker('refresh');
	
			if (campoOrigem.id == "selParte") {
				cod = "0165";
			}else{
				cod = "0365";
			}
			
			$("#" + campoOrigem.id).append("<option value='" + cod + "'>PARADA</option>");
	
			$("#" + campoOrigem.id).selectpicker('refresh');				
		}else if(jsonParteAtiv == undefined || jsonParteAtiv == ""){
			return $.ajax({
				url : url,
				data : {"recuperaDadosParteAtiv" :
							{  "codSecao": document.getElementById("inpSecao").value
							}
						},
				type : "post",
				success : function(data) {
					jsonParteAtiv = JSON.parse(data);
					interna_atualizaCampo(jsonParteAtiv);			
				}
			});
		}else{
			interna_atualizaCampo(jsonParteAtiv);			
		}
	
		function interna_atualizaCampo(dados) {
			var desc, cod, idValor = {};
			//exclui options anteriores do botão
			$("#" + campoOrigem.id + " option").remove();
			$("#" + campoOrigem.id).selectpicker('refresh');
	
			if (campoOrigem.id == "selParte") {
				cod = "PARTE";
				desc = "DESCRICAO_PARTE";
			}else if (campoOrigem.id == "selAtiv") {
				if (campoFiltro == "") {
					return bootbox.alert({
						buttons: {
							ok: {
								label: 'OK',
								className: 'bg text-light'
							},
						},
						centerVertical: true,
						title: "Seleção Inválida",
						message: "Favor selecionar a Parte antes da Atividade!",
					})
				}
				cod = "ATIV";
				desc = "DESCRICAO_ATIV";
			}
			Object.assign(idValor, {[cod]:{}});
			
			dados.filter(el => {
				if (cod == "PARTE" || campoFiltro == el["PARTE"]) {				
					codAtual = el[cod];
					descAtual = el[desc];
					posicaoAtual = {[codAtual]:descAtual};
					Object.assign(idValor[cod],  posicaoAtual);
				}
			});
			valorFim = Object.entries(idValor[cod]);
			for (var i = 0; i < valorFim.length; i++) {
				$("#" + campoOrigem.id).append("<option value='" + valorFim[i][0] + "'>" + valorFim[i][1] + "</option>");
			}
	
			$("#" + campoOrigem.id).selectpicker('refresh');		
		}	
	}	
}

//carrega valores dos nomes para supervisor
function js_recuperaNomeSup(padraoNome = "", padraoChapa = "" ) {	
	
	if (jsonSecaoNomeSup) {
		//limpa valores de nome e chapa para forçar o preenchimento
		$("#selNome option").remove();//remove nomes anteriores
		document.getElementById("inpChapa").value = "";
		var nomes = jsonSecaoNomeSup.filter(function(element) { return element.SECAO_DESCRICAO == document.getElementById("selSecaoDesc").value?element:""; });
		nomes.sort();
		if (nomes.length > 0) {
			for (var i = 0; i < nomes.length; i++) {
				$("#selNome").append("<option value='" + nomes[i]['NOME'] + "'>" + nomes[i]['NOME'] + "</option>");
			}
		}		
		$("#selNome").selectpicker('refresh');
		$('#selNome').selectpicker('val', '');

	}else{// carrega o nome do próprio colaborador logado
		$("#selNome option").remove();//remove nomes anteriores
		$("#selNome").append("<option value='" + padraoNome + "'>" + padraoNome + "</option>");				
		$("#selNome").selectpicker('refresh');
		$('#selNome').selectpicker('val', padraoNome);
		document.getElementById("inpChapa").value = padraoChapa;
	}
}

//carrega valores da secao para supervisor
function js_recuperaSecaoSup(campoID, login) {	
	url = '/functions/json.php';

	//carrega os dados da secao
	return $.ajax({
		url : url,
		data : {"recuperaSecaoNomeSup" :
					{  "usuarioLogado": login
					}
				},
		type : "post",
		success : function(data) {
			jsonSecaoNomeSup = JSON.parse(data);
			if (jsonSecaoNomeSup.length > 0) {
				for (var i = 0; i < jsonSecaoNomeSup.length; i++) { //alimenta os campos do select de seção na tela
					if (campoID.querySelectorAll("[value='" + jsonSecaoNomeSup[i]['SECAO_DESCRICAO'] + "']").length == 0) { //verifica se já existe a opção no select
						$("#" + campoID.id).append("<option value='" + jsonSecaoNomeSup[i]['SECAO_DESCRICAO'] + "'>" + jsonSecaoNomeSup[i]['SECAO_DESCRICAO'] + "</option>");
					}
				}
				$("#" + campoID.id).selectpicker('refresh');				
				js_recuperaNomeSup(); //carrega a lista de nomes a primeira vez na tela
			}
		}
	});
}

//Função para alterar o ID da seção ou da Chapa conforme forem sendo alterados
function js_alteraIDDesc(campo, valor) {
	var novoValor, origem, chaveValor;

	if (campo.id == "inpSecao") {
		origem = "SECAO_DESCRICAO";
		chaveValor = "SECAO";
	}else if(campo.id == "inpChapa"){
		origem = "NOME";
		chaveValor = "CHAPA";
	}

	if (jsonSecaoNomeSup) {
		novoValor =	jsonSecaoNomeSup.find(element => element[origem] == valor);
	
		if (novoValor) {
			campo.value = novoValor[chaveValor];
		}		
	}
}

//função para recuperar os valores dos campos ao iniciar a tela
function js_addEventosIniciais() {
	//preencher campos da OS
	$('#inpNumOS').on('change', function (element) {		
		js_tamanhoCampoOS(document.getElementById('inpNumOS'));
		js_recuperaDadosOS(document.getElementById('inpNumOS'));
		$("#selParte option").remove();
		$("#selParte").selectpicker("refresh");
		$("#selAtiv option").remove();
		$("#selAtiv").selectpicker("refresh");
	});	

	//retornar as partes e atividades
	$('#selParte, #selAtiv').on('shown.bs.select', function (e, clickedIndex, isSelected, previousValue) {
		js_recuperaDadosParteAtiv(this, $("#selParte").val());
	});

	
	//limpa os campos quando houver alteração
	$('#selSecaoDesc').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
		$("#selParte option").remove();
		$("#selParte").selectpicker("refresh");
		$("#selAtiv option").remove();
		$("#selAtiv").selectpicker("refresh");
		jsonParteAtiv = "";
		document.getElementById("inpChapa").value = "";
		$("#selNome option").remove();
		$("#selNome").selectpicker("refresh"); 
		js_recuperaNomeSup();
		js_alteraIDDesc(document.getElementById("inpSecao"), document.getElementById("selSecaoDesc").value);
	}); 
	$('#selNome').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
		js_alteraIDDesc(document.getElementById("inpChapa"),  document.getElementById("selNome").value);
		if (jsonSecaoNomeSup !== undefined) {
			var usu = jsonSecaoNomeSup.find(element => element['NOME'] == document.getElementById("selNome").value)
			js_ApontarValidaHora(1,usu==undefined?"":usu['LOGIN']);	
		}		
	});
	$('#selParte').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) { 
		$("#selAtiv option").remove();
		$("#selAtiv").selectpicker("refresh");
	}); 
}

//função para validar o preenchimento dos campos antes do envio do apontamento para o banco
function js_validaEnvioApont(evento, sup, usuLogado){
	var varInpChapa = document.getElementById("inpChapa");
	var varSelNome = document.getElementById("selNome"); 
	var varInpDataInicio = document.getElementById("inpDataInicio"); 
	var varInpDataFim = document.getElementById("inpDataFim"); 
	var varInpHoraInicio = document.getElementById("inpHoraInicio"); 
	var varInpHoraFim = document.getElementById("inpHoraFim"); 
	var varInpNumOS = document.getElementById("inpNumOS"); 
	var varInpOSDesc = document.getElementById("inpOSDesc"); 
	var varInpFilial = document.getElementById("inpFilial"); 
	var varInpCentroCusto = document.getElementById("inpCentroCusto"); 
	var varSelParte = document.getElementById("selParte"); 
	var varSelAtiv = document.getElementById("selAtiv");
	var varInpRetrabalho = document.getElementById("inpRetrabalho");
	var varSelCausaRetrabalho = document.getElementById("selCausaRetrabalho");
	var varInpServCampo = document.getElementById("inpServCampo");
	var varObservacao = document.getElementById("observacao");	

	try {		
		if (sup == "0"){//valida regras de campos para o colaborador
			if (varSelNome.value !== usuLogado || varInpChapa.value == "") { //usuario do apontamento precisa se o mesmo do usuario logado
				throw "Usuário inválido, atualize a página";
			}
		}
			if (varInpDataInicio.value !== varInpDataFim.value) {//as datas precisam ser iguais --VALIDAR HOJE
				varInpDataFim.value = varInpDataInicio.value;
			}

			if (varInpHoraInicio.value > varInpHoraFim.value) {
				throw "Hora início está maior que a hora final.";
			}

			if (varInpNumOS.value) { //valida o grupo de campo da OS
				if (varInpOSDesc.value == "" || varInpFilial.value == "" || varInpCentroCusto.value == "") {
					throw "Dados da OS não preenchidos, preencha o numero da OS novamente.";
				}
			}else{
				throw "Preencha o número da OS.";
			}

			if (varSelParte.value == "") {
				throw "Preencha o campo de Partes/Peças.";
			}else if (varSelAtiv.value == ""){
				throw "Preencha o campo de Atividades.";
			}

			if (varSelParte.value == 0 || varSelAtiv.value == 0) {
				throw "Parte/Peça ou Atividade não entrada para sua seção.";
			}

			if (varInpRetrabalho.checked == true && varSelCausaRetrabalho.value == "") {
				throw "Preencha o campo Causa do Retrabalho.";
			}else if (varInpRetrabalho.checked == false){
				$("#"+ varSelCausaRetrabalho.id).selectpicker('val', '');
				varInpRetrabalho.value = "NA";
			}

			if (varInpServCampo.checked == true) {
				varInpServCampo.value = "S";
			}else{
				varInpServCampo.value = "N";
			}

			if (varObservacao.value == "") {//insere um espaço na string para não enviar null
				varObservacao.value = " ";
			}
			
			var modalProcessando = bootbox.dialog({ 
				message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Salvando </div>', 
				closeButton: false,
				size: 'large',
				onEscape: false,
				centerVertical: true 
			}); 
			
			modalProcessando.modal('show');//exibe "Salvando" na tela

	} catch (e) { //cancela o envio do formulário
		evento.preventDefault();
		bootbox.alert({
			buttons: {
		        ok: {
		            label: 'OK',
		            className: 'bg text-light'
		        },
			},
			centerVertical: true,
		    title: "Apontamento Inválido",
		    message: e
		});
	}
}