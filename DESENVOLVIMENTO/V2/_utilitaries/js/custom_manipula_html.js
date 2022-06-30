//variaveis globais
var jsonSecaoNomeSup;
var jsonNomes;

//carrega permissões
function js_exibeCampos(classe_permissao, display = "", readOnly = "") {
	var campos = document.getElementsByClassName(classe_permissao); //busca todos os campos que são afetados pela permissão
	for (var i = 0; i < campos.length; i++) {
		campos[i].style.display = display;
		if (readOnly !== "") {
			if (campos[i].nodeName == "SELECT") {
				campos[i].disabled = readOnly;
				$('#' + campos[i].id).selectpicker('refresh');
			}else{
				campos[i].readOnly = readOnly;
			}
		}
		var inputs = document.querySelectorAll("." + classe_permissao + " .REQUIRED"); //informar para segundo nível de permissão
		for (var j = 0; j < inputs.length; j++) {
			var visibilidade = $("#"+inputs[i].id).is(":visible")
			if (display == "" && visibilidade == true) {
				inputs[i].required = true;
			}else{
				inputs[i].required = false;
			}
		}
	}
}
//Função para verificar hora fim do lançamento e lançamento com D+1 e D-1
function js_ApontarValidaHora(sup){

	var inpHoraInicio = document.getElementById("inpHoraInicio");
	var inpHoraFim = document.getElementById("inpHoraFim");
	var inpDataInicio = document.getElementById("inpDataInicio");

	if (inpHoraInicio.value != "" && inpHoraFim.value != "") { //verifica se já finalizou o preenchimento
		var dataInformada = new Date();
		var dataAlterada = new Date();
		var inpDataFim = document.getElementById("inpDataFim");

		//permite lançamento depois da meia-noite
		if (inpHoraInicio.value >= "14:30" && inpHoraFim.value<= "08:00") {
			//verifica se o lançamento é para o dia seguinte ou dia anterior
			if (sup == 1 && dataInformada.value != '') { //se for supervisor se baseia na hora lançada e não na hora atual
				var dataSplit = inpDataInicio.value.split('/');
				dataInformada = new Date(dataSplit[2], dataSplit[1], dataSplit[0]); //o lançamento é calculado pela data de início
				dataInformada.setHours(inpHoraInicio.value.substring(0,2));
				dataInformada.setMinutes(inpHoraInicio.value.substring(3,5));
			}

			if (dataInformada.getHours() >= "14" && dataInformada.getMinutes() >= "30") {
				dataAlterada.setDate(dataInformada.getDate() + 1);
				inpDataInicio.value = dataInformada.toLocaleDateString();
				inpDataFim.value = dataAlterada.toLocaleDateString();
			}else if(dataInformada.getHours() <= "08"){
				dataAlterada.setDate(dataInformada.getDate() - 1);
				inpDataInicio.value = dataAlterada.toLocaleDateString();
				inpDataFim.value = dataInformada.toLocaleDateString();
			}else{
				inpHoraFim.value = "";
				$(document).ready(function(){
					$(document.body)[0].innerHTML += '<div class="modal fade" id="modalValidaHora" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title text-danger" id="TituloModalCentralizado">Lançamento Inválido</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"><span aria-hidden="true"></span></button></div><div class="modal-body">Lançamento retroativo não é permitido!</div><div class="modal-footer"><button type="button" class="btn btn-secondary bg" data-bs-dismiss="modal">Fechar</button></div></div></div></div>';
					$("#modalValidaHora").modal("toggle");
				});
			}
		}else if(inpHoraFim.value <= inpHoraInicio.value){ //não permite lançamento com hora fim menor que hora início
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
		}else{ //lançamento primeiro turno
			if (inpDataInicio.value == '') {
				inpDataInicio.value = new Date().toLocaleDateString();
			}

			inpDataFim.value = inpDataInicio.value;
		}
	}
}

//função para carregar os dados da OS nos campos caso alguma OS genérica seja selecionada
function js_apontarSelecionaItem(id, descricao, filial, nomeCentroCusto, ){
	document.getElementById("inpNumOS").value = id;
	document.getElementById("inpOSDesc").value = descricao;
	document.getElementById("inpFilial").value = filial;
	document.getElementById("inpCentroCusto").value = nomeCentroCusto;
}

//função que valida o valor do campo de OS
function js_tamanhoCampoOS(campo){
	document.getElementById("inpOSDesc").value = "";
	document.getElementById("inpFilial").value = "";
	document.getElementById("inpCentroCusto").value = "";
	var valorCampo = campo.value;
	if (valorCampo.length == 5) {
		document.getElementById(campo.id).value = 0 + valorCampo;
	}else if (valorCampo.length !== 6) {
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
		//$(document.body)[0].innerHTML += '<div class="modal fade" id="modalTamanhoCampoOS" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title text-danger" id="TituloModalCentralizado">Número de OS inválido</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"><span aria-hidden="true"></span></button></div><div class="modal-body">OS informada não é válida!</div><div class="modal-footer"><button type="button" class="btn btn-secondary bg" data-bs-dismiss="modal">Fechar</button></div></div></div></div>';
		//$("#modalTamanhoCampoOS").modal("toggle");
	}
}

//função para preencher os campos da OS conforme click no botão de pesquisa da OS
function js_recuperaDadosOS(campoOS){
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
				if (retorno.length > 0) {
					document.getElementById("inpOSDesc").value = retorno[0]["TMOVCOMPL_DESCRICAOCOMP"];
					document.getElementById("inpFilial").value = retorno[0]["GFILIAL_CIDADE"];
					document.getElementById("inpCentroCusto").value = retorno[0]["GCCUSTO_NOME"];
				}else{
					bootbox.alert({
						buttons: {
					        ok: {
					            label: 'OK',
					            className: 'bg text-light'
					        },
						},
						centerVertical: true,
					    title: "OS não encontrada",
					    message: "Verifique o número digitado ou entre em contato com o TI através do GLPI!",
					});
				}
			}
		});
	}
}

//função para preencher os campos de Parte/Peça e atividade conforme a seção
function js_recuperaDadosParteAtiv(campoOrigem){
	url = '/functions/json.php';
	var codInterno, codInternoItem, codTabela;


	if (campoOrigem.id == 'selParte') {
		codInternoItem = 'PARTE';
		codTabela = 'SECAOPARTE';
		codInterno = document.getElementById("inpSecao").value;
	}else if (campoOrigem.id == 'selAtiv') {
		codInternoItem = 'ATIVIDADE';
		codTabela = 'PARTEATIV';
		codInterno = document.getElementById("selParte").value;
	}

	return $.ajax({
		url : url,
		data : {"recuperaDadosParteAtiv" :
					{  "codInternoItem": codInternoItem,
					   "codTabela": codTabela,
					   "codInterno": codInterno
					}
				},
		type : "post",
		success : function(data) {
			//exclui options anteriores do botão
			$("#" + campoOrigem.id + " option").remove();
			$("#" + campoOrigem.id).selectpicker('refresh');

			//carrega valores novos
			var retorno = JSON.parse(data);

			for (var i = 0; i < retorno.length; i++) {
				$("#" + campoOrigem.id).append("<option value='" + retorno[i]['ITEM'] + "'>" + retorno[i]['DESCRICAO'] + "</option>");
			}

			$("#" + campoOrigem.id).selectpicker('refresh');
			$("#" + campoOrigem.id).off("shown.bs.select"); //remove o evento de click para não carregar os valores novamente no botão
		}
	});

}

//Função para preencher os campos de Seção e nome para lançamentos de supervisor views->apontar.php
function js_recuperaSecaoNomeSup(campoID, campoDesc, origem) {
	url = '/functions/json.php';

	if (campoID.disabled == false || campoDesc.disabled == false) { //verifica se colaborador pode aprovar
		if (origem == "selecionarSecao") {
			var posArray = jsonSecaoNomeSup.find(element => element.SECAO_DESCRICAO == campoDesc.value);
			if (posArray !== undefined) {
				campoID.value = posArray["SECAO"];
				//limpa valores de nome, chapa, partes/peças e atividades para forçar o preenchimento
				$('#selNome').selectpicker('val', '');
				$("#selNome option").remove();//remove nomes anteriores					
				$('#selParte').selectpicker('val', '');
				$("#selParte option").remove();//remove nomes anteriores
				$('#selAtiv').selectpicker('val', '');
				$("#selAtiv option").remove();//remove nomes anteriores
				document.getElementById("inpChapa").value = "";
			}
		}else if (origem == "selecionarNome") {
			var posArray = jsonSecaoNomeSup.find(element => element.NOME == campoDesc.value);
			if (posArray !== undefined) {
				campoID.value = posArray["CHAPA"];
			}
		}else if (origem == "preencherNome") {
			if (jsonSecaoNomeSup == undefined) {
				bootbox.alert({
					buttons: {
				        ok: {
				            label: 'OK',
				            className: 'bg text-light'
				        },
					},
					centerVertical: true,
				    title: "Informar Seção",
				    message: "Favor selecionar a seção primeiro!",
				});
			}else{
				//limpa valores de nome e chapa para forçar o preenchimento
				$('#selNome').selectpicker('val', '');
				$("#selNome option").remove();//remove nomes anteriores
				document.getElementById("inpChapa").value = "";
				var nomes = jsonSecaoNomeSup.filter(function(element) { return element.SECAO == document.getElementById("inpSecao").value?element:""; });
				if (nomes.length > 0) {
					for (var i = 0; i < nomes.length; i++) {
						$("#" + campoDesc.id).append("<option value='" + nomes[i]['NOME'] + "'>" + nomes[i]['NOME'] + "</option>");
					}
					$("#" + campoDesc.id).selectpicker('refresh');
					$('#selNome').selectpicker('val', '');
				}
			}
		}else if (origem == "preencherSecao") {
			//carrega os dados
			return $.ajax({
				url : url,
				data : {"recuperaSecaoNomeSup" :
							{  "usuarioLogado": campoDesc
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
						$("#" + campoID.id).off("shown.bs.select"); //remove o evento de click para não carregar os valores novamente no botão
					}
				}
			});
		}
	}

}

//função para carregar os scripts iniciais da tela view/gerenciar.php
function funIniciaTimeGrid() {
	var varApontTime = document.getElementById('divApontTime');
	var varDiaAtual = new Date();
	var varTimeGrid = new FullCalendar.Calendar(varApontTime, {
		themeSystem: 'bootstrap5',
		initialView: 'timeGridDay', 
		initialDate: '2022-06-29', //só exibe o dia atual
		nowIndicator: true, //indica a hora atual 
		editable: false, //proibe edição do horario do evento
		selectable: true, //permite seleção de linha
		dayMaxEvents: true,
		slotEventOverlap: false, //não permite eventos sobrepostos visualmente
		allDaySlot: false, //não exibe opção de evento de "dia inteiro"
		contentHeight: 'auto', //não permite scroll no TimeGrid
		headerToolbar: {		
			left:'',			
			right: 'timeGridDay,listDay'
		},	
	});
	varTimeGrid.setOption('locale', 'pt-br');
	varTimeGrid.render();
}

//carrega calendario datepicker com a regra de data mínima para apontamento
function js_DataApontRetroativo(data) {
	$.datepicker.setDefaults($.datepicker.regional["pt-BR"]);
	var dataAtual = new Date();
	//minDate verifica se o parâmetro de lançamento retroativo no _utilitaries->config->ApontRetroativo está setado como true ou se o dia atual é anterior ao dia 5 para liberar apontamento no mês anterior
	$.datepicker.setDefaults({
		format:'yyyy-mm-dd',
		maxDate: new Date(),
		minDate: new Date(dataAtual.getFullYear(),(data == 1 || dataAtual.getDate() <= 5)?dataAtual.getMonth()-1:dataAtual.getMonth(), 1),
	});
}


//função para pesquisar apontamentos na tela de gerenciar
function js_pesquisaGerenciar(dataPesquisa, login, sup) {
	url = '/functions/json.php';

	if (sup == true && jsonNomes !== "") {
		stringLogin = "'JENIFER.MENDES','LAISLA.COSTA', 'LUCAS.AMARO'";
	}else{
		stringLogin = "'JENIFER.MENDES'";
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
			jsonApontamentos = JSON.parse(data);
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
				for (var i = 0; i < jsonNomes.length; i++) { //alimenta o campo do select de seção na tela
					$("#" + id_sup.id).append("<option value='" + jsonNomes[i]['LOGIN'] + "'>" + jsonNomes[i]['NOME'] + "</option>");					
				}
				$("#" + id_sup.id).selectpicker('refresh');
				$("#" + id_sup.id).off("shown.bs.select"); //remove o evento de click para não carregar os valores novamente no botão
			}
		}
	});
}