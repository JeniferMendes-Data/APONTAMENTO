<?php
if (session_status() == 1){
    session_start();
}
include $_SERVER["DOCUMENT_ROOT"].'/functions/include.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/functions/global_functions.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/_utilitaries/config.php';
?>
<!doctype html>
<html lang="pt-br">
	<head>
		<?php echo include_head('APONTAMENTO | APONTAR');?>
	</head>
	<body>
    	<?php
        		//verifica se a sessão está ativa
        		 if (!isset($_SESSION["nomeUsuario"])) {
        		    header("Location: ../index.php");
        		    die();
        		}
        		echo include_menu("Apontar","Apontar Horas");

        		//verifica permissões do usuário
				$readyPermissoes = "";
        		if (isset($_SESSION["APT"]) && $_SESSION["APT"] == 1) {
        		    $readyPermissoes .= "js_exibeCampos('APT', '', false);";//prepara string para inserir no onReady da página para cada permissão
        		}
        		//verifica permissões do usuário
        		if (isset($_SESSION["APV"]) && $_SESSION["APV"] == 1) {
        		    $readyPermissoes .= "js_exibeCampos('APV', '', false);"; //prepara string para inserir no onReady da página para cada permissão
        		}

        		//retorno de inclusão de apontamento
        		if (isset($_SESSION["msg"])) {
        		    $mensagem = $_SESSION["msg"];
        		    unset($_SESSION["msg"]);
        		    //mensagem de erro/sucesso
        		    echo include_modal("idRetornoApontar","Apontamento de Horas", $mensagem, "info");
        		}

    	?>
    	<form action="../functions/post.php?pag=apontar" method="post" id="salvaApont" onsubmit="js_validaEnvioApont()" >
    	<script language= "JavaScript">$(document).ready(function(){ $("#idRetornoApontar").modal("show"); });</script>
        	<div class="container">
        		<div id="dadosColab" class="row">
        			<div class="col-md-2">
        				<label for="inpSecao">Seção:</label>
        				<input id="inpSecao" name="inpSecao" type="text" class="form-control" readOnly value="<?php echo $_SESSION['codSecao'];?>"></input>
        			</div>
        			<div class="col-md-3">
        				<label for="selSecaoDesc">Descrição:</label>
        				<select id="selSecaoDesc" name="selSecaoDesc" type="text" class="selectpicker form-control border APV" data-live-search="true" data-style="btn" disabled>
        					<option value="<?php echo $_SESSION['secaoDesc'];?>"><?php echo $_SESSION['secaoDesc'];?></option>
        				</select>
        			</div>
        			<div class="col-md-2 d-none d-md-block">
        				<label for="inpChapa">Chapa:</label>
        				<input id="inpChapa" name="inpChapa" type="number" class="form-control" readOnly value="<?php echo $_SESSION['chapa'];?>"></input>
        			</div>
        			<div class="col-md-5">
        				<label for="selNome">Nome:</label>
        				<select id="selNome" name="selNome" type="text" class="selectpicker form-control border APV" data-live-search="true" data-style="btn" disabled>
        					<option value="<?php echo $_SESSION['nomeUsuario'];?>"><?php echo $_SESSION['nomeUsuario'];?></option>
        				</select>
        			</div>
        		</div>
        		<div id="dadosLanc" class="row">
        			<div class="col-md-2">
        				<label for="inpDataInicio">Data Início:</label>
        				<input type="text" class="form-control APV" id="inpDataInicio" name="inpDataInicio" autocomplete="off" readOnly required></input>
        			</div>
        			<div class="col-md-4">
        				<label for="inpHoraInicio">Hora Início:</label>
        				<input id="inpHoraInicio" name="inpHoraInicio" type="time" class="form-control APT APV" readOnly onblur="js_ApontarValidaHora('<?php if (isset($_SESSION["APV"])){ echo $_SESSION["APV"];}else{ echo 0;}  ?>', document.getElementById('inpDataInicio'))" required></input>
        			</div>
        			<div class="col-md-2">
        				<label for="inpDataFim">Data Fim:</label>
        				<input type="text" class="form-control" id="inpDataFim" name="inpDataFim" readOnly></input>
        			</div>
        			<div class="col-md-4">
        				<label for="inpHoraFim">Hora Fim:</label>
        				<input id="inpHoraFim" name="inpHoraFim" type="time" class="form-control APT APV" onblur="js_ApontarValidaHora('<?php if (isset($_SESSION["APV"])){ echo $_SESSION["APV"];}else{ echo 0;} ?>', document.getElementById('inpDataInicio'))" required readOnly></input>
        			</div>
        		</div>
        		<div id="dadosOS" class="row">
        			<div class="col-md-3">
        				<label for="inpNumOS">Número OS:</label>
        				<div class="input-group">
        					<div class="input-group-append">
        						<button type="button" class="input-group-text dropdown dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        							<span class="material-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="OS Genérica">list</span>
        						</button>
        						<div id="dpdownOSGenerica" class="dropdown-menu" style="max-height: 200px; overflow-y: scroll;">
        							<!-- Preenchido dinamicamente-->
                                </div>
        					</div>
        					<input id="inpNumOS" name="inpNumOS" type="number" class="form-control APT APV" maxlength="6" required readOnly></input>
        				</div>
        			</div>
        			<div class="col-md-4">
        				<label for="inpOSDesc">Descrição OS:</label>
        				<input id="inpOSDesc" name="inpOSDesc" type="text" class="form-control" readOnly></input>
        			</div>
        			<div class="col-md-2 d-none d-md-block">
        				<label for="inpFilial">Filial:</label>
        				<input id="inpFilial" name="inpFilial" type="text" class="form-control" readOnly></input>
        				<input id="inpIdFilial" name="inpIdFilial" type="text" class="form-control" style="display:none;"></input>
        			</div>
        			<div class="col-md-3 d-none d-md-block">
        				<label for="inpCentroCusto">Centro de Custo:</label>
        				<input id="inpCentroCusto" name="inpCentroCusto" type="text" class="form-control" readOnly></input>
        			</div>
        		</div>
        		<div id="dadosAtiv" class="row">
        			<div class="col-md-6">
        				<label for="selParte">Partes/Peças:</label>
                       <select id="selParte" name="selParte" class="selectpicker form-control border APT APV" data-live-search="true" title="Selecionar Parte/Peça" data-style="btn" disabled>
                        </select>
                    </div>
        			<div class="col-md-6">
    					<label for="selAtiv">Atividades:</label>
        				<select id="selAtiv" name="selAtiv" class="selectpicker form-control border APT APV" data-live-search="true" title="Selecionar Atividade" data-style="btn" disabled>
                        </select>
        			</div>
        		</div>
        		<div id="dadosRetrab" class="row mt-2">
        			<div class="col-md-2 form-check mt-2">
        				<input id="inpRetrabalho" name="inpRetrabalho" type="checkbox" readOnly class="form-check-input-inline mr-1 APT APV" onclick="js_exibeCampos('CAUSA_RETRABALHO', document.getElementById('inpRetrabalho').checked==true?'':'none')"></input>
        				<label class="form-check-label-inline" for="inpRetrabalho">Retrabalho</label>
        			</div>
        			<div class="col-md-6 CAUSA_RETRABALHO" style="display:none;">
        				<select id="selCausaRetrabalho" name="selCausaRetrabalho" class="selectpicker form-control border" data-live-search="true" title="Selecionar Causa do Retrabalho" data-style="btn"></select>
        			</div>
        			<div class="col-md-3 form-check mt-2">
        				<input id="inpServCampo" name="inpServCampo" type="checkbox" readOnly class="form-check-input-inline mr-1 APT APV"></input>
    					<label class="form-check-label-inline" for="inpServCampo">Serviço de Campo</label>
        			</div>
        		</div>
        		<div id="salvar" class="row justify-content-end mt-5 APT APV" style="display:none;">
    				<button type="submit" class="btn btn-primary col-md-1">Salvar</button>
        		</div>
        	</div>
        </form>
	</body>
	<script>
		$(document).ready(function(){

			//retira o envio do formulário pelo enter
			$('form#salvaApont').keypress(function(e) {
                if ((e.keyCode == 10)||(e.keyCode == 13)) {
                    e.preventDefault();
                }
            });

			//preencher campos da OS
            $('#inpNumOS').on('change', function (element) {
				js_tamanhoCampoOS(document.getElementById('inpNumOS'));
            });

			if(<?php if (isset($_SESSION["APV"])){ echo $_SESSION["APV"];}else{ echo 0;} ?> == true){ //verifica se colaborador pode aprovar

				//inicializa calendário em campos de datas

    			$.datepicker.setDefaults($.datepicker.regional["pt-BR"]);
    			var dataAtual = new Date();
    			//minDate verifica se o parâmetro de lançamento retroativo no _utilitaries->config->ApontRetroativo está setado como true ou se o dia atual é anterior ao dia 5 para liberar apontamento no mês anterior
    			$.datepicker.setDefaults({
    				format:'yyyy-mm-dd',
    				maxDate: new Date(),
    				minDate: new Date(dataAtual.getFullYear(),((<?php $config = new Config(); echo $config->diaApontRetroativo; ?> == true) || dataAtual.getDate() <= 5)?dataAtual.getMonth()-1:dataAtual.getMonth(), 1),
    			});

				$("#inpDataInicio").datepicker({
					onSelect: function(value, date){
                    	js_ApontarValidaHora('<?php if (isset($_SESSION["APV"])){ echo $_SESSION["APV"];}else{ echo 0;}  ?>', document.getElementById('inpDataInicio'));
                    }
                });

    			$('#selSecaoDesc').on('shown.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    				js_recuperaSecaoNomeSup(document.getElementById("selSecaoDesc"), "<?php echo $_SESSION["usuarioLogado"];?>", "preencherSecao");
    			});

    			$('#selNome').on('shown.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    				js_recuperaSecaoNomeSup(document.getElementById("inpChapa"), document.getElementById("selNome"), "preencherNome");
    			});

    			$('#selSecaoDesc').on('change', function (element) {
                  js_recuperaSecaoNomeSup(document.getElementById("selSecaoDesc"), document.getElementById("inpSecaoDesc"), "selecionarSecao");
                });

    			$('#selNome').on('change', function (element) {
                  js_recuperaSecaoNomeSup(document.getElementById("inpChapa"), document.getElementById("selNome"), "selecionarNome");
                });
			}

            //preencher campo da seção e nomes de colaborador
            $('#inpNumOS').on('change', function (e, clickedIndex, isSelected, previousValue) {
				js_recuperaDadosOS(document.getElementById('inpNumOS'));
            });

			//retornar as partes e atividades
			$('#selParte').on('shown.bs.select', function (e, clickedIndex, isSelected, previousValue) {
              js_recuperaDadosParteAtiv(this);
            });

            $('#selAtiv').on('shown.bs.select', function (e, clickedIndex, isSelected, previousValue) {
              js_recuperaDadosParteAtiv(this);
            });

			//recarrega a atividade para cada vez que a parte for alterada
            $('#selParte').change(function (e, clickedIndex, isSelected, previousValue) {
              $("#selAtiv option").remove();
              $("#selAtiv").selectpicker("refresh");
              $('#selAtiv').on('shown.bs.select', function (e, clickedIndex, isSelected, previousValue) {
                  js_recuperaDadosParteAtiv(this);
                });
            });

			$('#selAtiv').on('shown.bs.select', function (e, clickedIndex, isSelected, previousValue) {
              js_recuperaDadosParteAtiv(this);
            });

            //retorna as causas do retrabalho
            document.getElementById('selCausaRetrabalho').innerHTML = '<?php echo include_causaRetrabalho(); ?>';
			$("#selCausaRetrabalho").selectpicker("refresh");

			//retornar as OS's genéricas
        	document.getElementById('dpdownOSGenerica').innerHTML = '<?php echo include_itemOSGenerica(); ?>';

    		//chama função no js que carrega os campos conforme permissão selecionada via PHP
    		<?php
    		  echo ($readyPermissoes);
    		?>



        });
	</script>
</html>