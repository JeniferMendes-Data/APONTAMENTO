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
				$readyPermissoes = global_verificaPermissao();			

        		//retorno de inclusão de apontamento
        		if (isset($_SESSION["msg"])) {
        		    $mensagem = $_SESSION["msg"];
        		    unset($_SESSION["msg"]);
        		    //mensagem de erro/sucesso chama modal bootbox
					echo "<script>$(document).ready(function(){bootbox.alert({buttons: {ok: {label: 'Fechar',className: 'bg text-light'},},centerVertical: true,title: 'Apontamento de Horas',message: '$mensagem'});})</script>"; 														                 		    
        		}
    	?>
    	<form action="../functions/post.php?pag=apontar" method="post" id="salvaApont" onsubmit="js_validaEnvioApont(event, '<?php if (isset($_SESSION['APV'])){ echo $_SESSION['APV'];}else{ echo 0;} ?>', '<?php echo $_SESSION['nomeUsuario'];?>')" >
    	<div class="container">
        		<div id="dadosColab" class="row">
        			<div class="col-md-2">
        				<label for="inpSecao">Seção:</label>
        				<input id="inpSecao" name="inpSecao" type="text" class="form-control" readOnly value="<?php echo $_SESSION['codSecao'];?>"></input>
        			</div>
        			<div class="col-md-3">
        				<label for="selSecaoDesc">Descrição:</label>
        				<select id="selSecaoDesc" name="selSecaoDesc" type="text" class="form-control border APV" data-live-search="true" data-style="btn" title="Selecionar Seção" disabled required>
        					<option value="<?php echo $_SESSION['secaoDesc'];?>"><?php echo $_SESSION['secaoDesc'];?></option>
        				</select>
        			</div>
        			<div class="col-md-2 d-none d-md-block">
        				<label for="inpChapa">Chapa:</label>
        				<input id="inpChapa" name="inpChapa" type="number" class="form-control" readOnly value="<?php echo $_SESSION['chapa'];?>"></input>
        			</div>
        			<div class="col-md-5">
        				<label for="selNome">Nome:</label>
        				<select id="selNome" name="selNome" type="text" class="form-control border APV" data-live-search="true" data-style="btn" title="Selecionar Nome" disabled required>
        					<option value="<?php echo $_SESSION['nomeUsuario'];?>"><?php echo $_SESSION['nomeUsuario'];?></option>
        				</select>
        			</div>
        		</div>
        		<div id="dadosLanc" class="row">
        			<div class="col-md-3">
        				<label for="inpDataInicio">Data Início:</label>
        				<input type="text" class="form-control APV" id="inpDataInicio" name="inpDataInicio" autocomplete="off" readOnly required></input>
        			</div>
        			<div class="col-md-3">						
						<label for="inpHoraInicio">Hora Início:</label>
						<input id="inpHoraInicio" name="inpHoraInicio" class="form-control APT APV" type="text" onblur="js_ApontarValidaHora('<?php if (isset($_SESSION['APV'])){ echo $_SESSION['APV'];}else{ echo 0;} ?>', '<?php echo $_SESSION['usuarioLogado'];?>')" readOnly required></input>
        			</div>
        			<div class="col-md-3">
        				<label for="inpDataFim">Data Fim:</label>
        				<input type="text" class="form-control" id="inpDataFim" name="inpDataFim" readOnly></input>
        			</div>
        			<div class="col-md-3">						
						<label for="inpHoraFim">Hora Fim:</label>
						<input id="inpHoraFim" name="inpHoraFim" class="form-control APT APV" type="text" onblur="js_ApontarValidaHora('<?php if (isset($_SESSION['APV'])){ echo $_SESSION['APV'];}else{ echo 0;} ?>', '<?php echo $_SESSION['usuarioLogado'];?>')" readOnly required></input>					
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
                       <select id="selParte" name="selParte" class="selectpicker form-control border APT APV" data-live-search="true" title="Selecionar Parte/Peça" data-style="btn" disabled required>
                        </select>
                    </div>
        			<div class="col-md-6">
    					<label for="selAtiv">Atividades:</label>
        				<select id="selAtiv" name="selAtiv" class="selectpicker form-control border APT APV" data-live-search="true" title="Selecionar Atividade" data-style="btn" disabled required>
                        </select>
        			</div>
        		</div>
        		<div id="dadosRetrab" class="row mt-2">
        			<div class="col-md-2 form-check mt-2">
        				<input id="inpRetrabalho" name="inpRetrabalho" type="checkbox" readOnly class="form-check-input-inline mr-1 APT APV" onclick="js_exibeCampos('CAUSA_RETRABALHO', document.getElementById('inpRetrabalho').checked==true?'':'none')"></input>
        				<label class="form-check-label-inline" for="inpRetrabalho">Retrabalho</label>
        			</div>
        			<div class="col-md-6 CAUSA_RETRABALHO REQUIRED" style="display:none;">
        				<select id="selCausaRetrabalho" name="selCausaRetrabalho" class="selectpicker form-control border" data-live-search="true" title="Selecionar Causa do Retrabalho" data-style="btn"></select>
        			</div>
        			<div id='divServCampo' class="col-md-3 form-check mt-2">
        				<input id="inpServCampo" name="inpServCampo" type="checkbox" readOnly class="form-check-input-inline mr-1 APT APV"></input>
    					<label class="form-check-label-inline" for="inpServCampo">Serviço de Campo</label>
        			</div>
        		</div>
				<div class="row mt-2 ">
					<label class="text"  for="inptext">Observação</label>
					<div class="col-md">
						<textarea placeholder="Observação" id="observacao" name="observacao" cols=30 rows="3" class="form-control" maxlength="254"></textarea>
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
			
			//inicializa a selecao
			$('#selSecaoDesc').selectpicker(); 
			$('#selNome').selectpicker();

			//inicializa os campos de hora
			$("#inpHoraFim, #inpHoraInicio").inputmask("datetime",{placeholder:"--:--",outputFormat: "HH:MM", clearMaskOnLostFocus:false, inputFormat: "HH:MM", autoUnmask: true});

			if(<?php if (isset($_SESSION["APV"])){ echo $_SESSION["APV"];}else{ echo 0;} ?> == true){ //verifica se colaborador pode aprovar

				//inicializa calendário em campos de datas
				var minDate = js_DataApontRetroativo((<?php $config = new Config(); echo $config->diaApontRetroativo; ?> == true), <?php echo $_SESSION["APT_RET"]; ?>); //define o menor dia disponível para apontamento

				$("#inpDataInicio").datepicker({
					format: 'dd/mm/yyyy',
    				startDate: minDate,
					language: 'pt-BR',
					endDate: Date(),
					todayHighlight: true,
					autoclose: true
                }); 

				$("#inpDataInicio").on('changeDate', function(){
                    	js_ApontarValidaHora('<?php if (isset($_SESSION["APV"])){ echo $_SESSION["APV"];}else{ echo 0;}  ?>', '<?php echo $_SESSION['usuarioLogado'];?>', document.getElementById('inpDataInicio'));
                    })
				
				//carrega seções para supervisor
				$('#selSecaoDesc').on('loaded.bs.select', function (e, clickedIndex, isSelected, previousValue) {
					js_recuperaSecaoSup(this, '<?php echo $_SESSION['usuarioLogado'];?>');
				}); 
			}
			js_addEventosIniciais();
			
			//retorna as causas do retrabalho
			document.getElementById('selCausaRetrabalho').innerHTML = '<?php echo include_causaRetrabalho(); ?>';
			$("#selCausaRetrabalho option[value='NA']").remove();
			$("#selCausaRetrabalho").selectpicker("refresh");

			//retornar as OS's genéricas
			document.getElementById('dpdownOSGenerica').innerHTML = '<?php echo include_itemOSGenerica(); ?>';

			//inicializa a Descrição da seção e nome do colaborador logado
			$('#selSecaoDesc').selectpicker('val', '<?php echo $_SESSION['secaoDesc'];?>');
			js_recuperaNomeSup('<?php echo $_SESSION['nomeUsuario'];?>', '<?php echo $_SESSION['chapa'];?>');
			
    		//chama função no js que carrega os campos conforme permissão selecionada via PHP
    		<?php
    		  echo ($readyPermissoes);
    		?>
			//bloqueia novamente o serviço de campo para coligada XL
			if (<?php echo $_SESSION['coligada']; ?> == 2) {
				document.getElementById("inpServCampo").value = "N";
				document.getElementById("inpServCampo").classList.remove('APT', 'APV'); //remove permissões de edição
				document.getElementById("divServCampo").style.display = 'none';
			}
			
        });
		//oculta modal de processando apontamento
		// $(window).ready(function () {
  
		// 	$("#salvaApont").submit(function (e) {				
		// 		bootbox.hideAll();
		// 		console.log(e);
  		// 	})
		// })
	</script>
</html>