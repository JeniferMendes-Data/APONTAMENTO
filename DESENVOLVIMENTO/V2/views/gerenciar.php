<?php
if (session_status() == 1){
    session_start();
}
include $_SERVER["DOCUMENT_ROOT"].'/functions/include.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/functions/global_functions.php';
?>
<!doctype html>
<html lang="pt-br">
	<head>
		<?php echo include_head('APONTAMENTO | GERENCIAR') ;?>
	</head>
	<body>
    	<?php
            	//verifica se a sessão está ativa
            	if (!isset($_SESSION["nomeUsuario"])) {
            	    header("Location: ../index.php");
            	    die();
            	}else if(!isset($_GET["origem"]) && $_GET["origem"] == "login"){
            	    //chama modal bootbox
					echo "<script>$(document).ready(function(){bootbox.alert({buttons: {ok: {label: 'Fechar',className: 'bg text-light'},},centerVertical: true,title: 'Apontamento de Horas',message: 'Bem-vindo ".$_SESSION['nomeUsuario']."!'});})</script>"; 						
            	}
        		echo include_menu("Gerenciar","Gerenciar Apontamentos");

				//verifica permissões do usuário
				$readyPermissoes = global_verificaPermissao();
    	?>
		<div class="container">
			<div id="dadosColab" class="row align-items-end justify-content-center">
				<div class="col-md-2">
					<div class="form-group">
						<label for="inpDataFiltro">Data:</label>
						<input type="text" class="form-control" name="inpDataFiltro" id="inpDataFiltro">
					</div>
				</div>
				<div class="col-md-4">
					<label for="selNome">Nome:</label>
					<select id="selNome" name="selNome" type="text" class="selectpicker form-control border APV" data-live-search="true" data-style="btn" disabled>
						<option value="<?php echo $_SESSION['usuarioLogado'];?>"><?php echo $_SESSION['nomeUsuario'];?></option>
					</select>
				</div>
				<div class="col-md-3">
					<label for="selAcao">Ação:</label>
					<select id="selAcao" name="selAcao" type="text" class="selectpicker form-control border APV" data-live-search="false" data-style="btn" disabled>
						<option value="1">Consultar</option>
						<option value="2">Aprovar</option>
					</select>
				</div>
				<div class="col-md-1 text-end mt-2">
					<div class="form-group">
						<button type="button" name="" id="" class="btn bg text-light flex-row" onclick="js_pesquisaGerenciar(document.getElementById('inpDataFiltro').value.split('/').reverse().join('/'), document.getElementById('selNome').value, document.getElementById('selAcao').value, <?php if (isset($_SESSION['APV'])){ echo $_SESSION['APV'];}else{ echo 0;} ?>)"><span class="material-icons">search</span></button>
					</div>
				</div>
			</div>			
			<div class="row mt-2">
				<div class="accordion" id="divSecaoInd">	
					<!-- preenchido dinâmicamente-->
				</div>
			</div>
			<div id="calendario">
				<div class="mt-2" id="divApontTime">		
					<!-- preenchido dinâmicamente-->	  
				</div>
				<div id="divCarregando" style="display:none;">
					<img id="imgCarregando" src=<?php echo "http://".$_SERVER["HTTP_HOST"]."/_utilitaries/img/loading.gif";?> alt="Carregando..." />
				</div>
			</div>
		</div>
		<div class="modal fade" id="divEditAprov" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="lblEditAprov" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="lblEditAprov" data-tit></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form>
					<div class="row mb-3">
						<label for="inpSecao" class="col-sm-1 col-form-label col-form-label-sm">Seção:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control col-form-label-sm ENVIAR" id="inpSecao" name="inpSecao" readOnly></input>
						</div>
					</div>
					<div class="row mb-3">
						<label for="inpNumOS" class="col-sm-1 col-form-label col-form-label-sm">OS:</label>
        				<div class="col-sm-5">
							<div class="input-group">
								<div class="input-group-append">
									<button id="btnOSGenerica" type="button" class="input-group-text dropdown dropdown-toggle-split EDIT" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="material-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="OS Genérica">list</span>
									</button>
									<div id="dpdownOSGenerica" class="dropdown-menu" style="max-height: 200px; overflow-y: scroll;">
										<!-- Preenchido dinamicamente-->
									</div>
								</div>
								<input id="inpNumOS" name="inpNumOS" type="number" class="form-control col-form-label-sm EDIT ENVIAR" maxlength="6" required readOnly></input>
							</div>				
						</div>
						<label for="inpDataInicio" class="col-sm-1 col-form-label col-form-label-sm">Data:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control col-form-label-sm ENVIAR" id="inpDataInicio" name="inpDataInicio" readOnly></input>
						</div>
					</div>
					<div class="row mb-3 align-items-center">
						<label for="inpHoraInicio" class="col-sm-1 col-form-label col-form-label-sm">Hora Início:</label>
						<div class="col-sm-5">
							<input type="time" class="form-control col-form-label-sm EDIT ENVIAR" id="inpHoraInicio" name="inpHoraInicio" required readOnly></input>
						</div>
						<label for="inpHoraFim" class="col-sm-1 col-form-label col-form-label-sm">Hora Fim:</label>
						<div class="col-sm-5">
							<input type="time" class="form-control col-form-label-sm EDIT ENVIAR" id="inpHoraFim" name="inpHoraFim" required readOnly></input>
						</div>
					</div>
					<div class="row mb-3 align-items-center">                       
						<label for="selParte" class="col-sm-2 col-form-label col-form-label-sm">Partes/Peças:</label>
						<div class="col-sm-10">
							<select id="selParte" name="selParte" class="form-control border EDIT ENVIAR" data-live-search="true" title="Selecionar Parte/Peça" data-style="btn" required disabled></select>
						</div>
					</div>
					<div class="row mb-3 align-items-center">
						<label for="selAtiv" class="col-sm-2 col-form-label col-form-label-sm">Atividade:</label>
						<div class="col-sm-10">
							<select id="selAtiv" name="selAtiv" class="form-control border EDIT ENVIAR" data-live-search="true" title="Selecionar Atividade" data-style="btn" required disabled></select>
						</div>
					</div>
					<div class="row mb-3 align-items-center">
						<label for="selCausaRetrabalho" class="col-sm-2 col-form-label col-form-label-sm">Retrabalho:</label>
						<div class="col-sm-10">
							<select id="selCausaRetrabalho" name="selCausaRetrabalho" class="form-control border EDIT ENVIAR" data-live-search="true" title="Selecionar Causa do Retrabalho" data-style="btn" required disabled></select>
						</div>
					</div>
					<div class="row mb-3 align-items-center">						
						<div class="col-sm-1">
							<input id="inpServCampo" name="inpServCampo" type="checkbox" class="form-check-input-inline mr-1 EDIT ENVIAR"  required disabled></input>
						</div>
						<label for="inpServCampo" class="col-sm-11 col-form-label col-form-label-sm">Serviço de Campo</label>
					</div>
					<div class="row mb-3">
						<div class="col-sm-12">
							<textarea placeholder="Observação" id="inpObs" name="inpObs" cols=30 rows="3" class="form-control EDIT ENVIAR" maxlength="254"></textarea>
						</div>
					</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="btnAprovar" style="display:none;">Aprovar</button>										
					<button type="button" class="btn btn-primary" id="btnEditar" style="display:none;">Salvar</button>	
					<button type="button" class="btn btn-secondary" id="btnFechar" data-bs-dismiss="modal"></button>					
				</div>
			</div>
		</div>
	</body>
	<script>
		$(document).ready(function(){
			$('#divCarregando').show(); //exibe gif de carregamento no calendário
			//retornar as OS's genéricas
			document.getElementById('dpdownOSGenerica').innerHTML = '<?php echo include_itemOSGenerica("ger"); ?>';
			
			//retorna as causas do retrabalho
			document.getElementById('selCausaRetrabalho').innerHTML = '<?php echo include_causaRetrabalho(); ?>';
			$("#selCausaRetrabalho").selectpicker("refresh");

			//verifica se colaborador pode aprovar
			var sup = <?php if (isset($_SESSION["APV"])){ echo $_SESSION["APV"];}else{ echo 0;} ?>;
			funIniciaTimeGrid(sup);
			// $("#inpDataFiltro").datepicker();
			// var minDate = (<?php $config = new Config(); echo $config->diaApontRetroativo; ?> == true);
			// js_DataApontRetroativo(minDate, <?php echo $_SESSION["APT_RET"]; ?>); //define minDate no calendário			
			// $("#inpDataFiltro").datepicker("setDate", new Date().toLocaleDateString('en-ZA'));
			
			//libera a seleção de nomes
			// (sup == true)?
			// 	js_recuperaNomeIDSup(document.getElementById("selNome")):
			// 	js_pesquisaGerenciar(new Date().toLocaleDateString('en-ZA'), document.getElementById("selNome").value,document.getElementById("selAcao").value, false);
						
			//chama função no js que carrega os campos conforme permissão selecionada via PHP
    		<?php
    		  echo ($readyPermissoes);
    		?>
		});	
	</script>
</html> 