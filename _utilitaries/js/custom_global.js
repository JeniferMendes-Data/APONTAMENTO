//variaveis globais (Para todos os arquivos custom_*.js)
var jsonSecaoNomeSup;
var jsonNomes;
var jsonParteAtiv;
var global_calendario;
var global_aprov;
var global_selectApvRpv;

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
		var inputs = document.querySelectorAll("." + classe_permissao + ".REQUIRED"); //informar para segundo nível de permissão
		for (var j = 0; j < inputs.length; j++) {
			if (inputs[i].id !== "") {
				var visibilidade = $("#"+inputs[i].id).is(":visible")
				if (display == "" && visibilidade == true) {
					inputs[i].required = true;
				}else{
					inputs[i].required = false;
				}
			}
		}
	}
}

//carrega calendario datepicker com a regra de data mínima para apontamento
function js_DataApontRetroativo(data, permissao) {
	var dataAtual = new Date();
	var dataCalendario;	
	
	if (permissao){//APT_RET - PERMISSÃO PARA APONTAR/APROVAR EM QUALQUER DATA RETROATIVA
		dataCalendario = new Date(dataAtual.getFullYear(), 0, 1);
	}else if (data == 1 || dataAtual.getDate() <= 5){ //minDate verifica se o parâmetro de lançamento retroativo no _utilitaries->config->ApontRetroativo está setado como true ou se o dia atual é anterior ao dia 5 para liberar apontamento no mês anterior
		dataCalendario = new Date(dataAtual.getFullYear(), dataAtual.getMonth()-1, 1);
	}else{
		dataCalendario = new Date(dataAtual.getFullYear(), dataAtual.getMonth(), 1);
	}
	
	return dataCalendario;
}

//função para ordenar options de um select apos serem inseridos no campo
function global_ordenaOption(idSel){
	$("#" + idSel).html($("#" + idSel +" option").sort(function (a, b) {
		return a.text == b.text ? 0 : a.text < b.text ? -1 : 1
	}));    
}
