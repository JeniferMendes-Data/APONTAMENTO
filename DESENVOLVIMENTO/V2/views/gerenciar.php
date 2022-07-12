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
            	    echo include_modal("idRetornoLogin","Apontamento de Horas", "Bem-vindo ".$_SESSION['nomeUsuario']. "!", "sucesso");
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
				<div class="col-md-5">
					<label for="selNome">Nome:</label>
					<select id="selNome" name="selNome" type="text" class="selectpicker form-control border APV" data-live-search="true" data-style="btn" disabled>
						<option value="<?php echo $_SESSION['usuarioLogado'];?>"><?php echo $_SESSION['nomeUsuario'];?></option>
					</select>
				</div>
				<div class="col-md-1 text-end mt-2">
					<div class="form-group">
						<button type="button" name="" id="" class="btn bg text-light flex-row" onclick="js_pesquisaGerenciar(document.getElementById('inpDataFiltro').value.split('/').reverse().join('/'), document.getElementById('selNome').value, <?php if (isset($_SESSION['APV'])){ echo $_SESSION['APV'];}else{ echo 0;} ?>)"><span class="material-icons">search</span></button>
					</div>
				</div>
			</div>			
			<div class="row mt-2">
				<div class="accordion" id="divSecaoInd">					
				</div>
			</div>
			<div id="calendario">
				<div class="mt-2" id="divApontTime">			  
				</div>
				<div id="divCarregando" style="display:none;">
					<img id="imgCarregando" src=<?php echo "http://".$_SERVER["HTTP_HOST"]."/_utilitaries/img/loading.gif";?> alt="Carregando..." />
				</div>
			</div>
		</div>
		<div class="modal fade" id="divEditAprov" tabindex="-1" aria-labelledby="lblEditAprov" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="lblEditAprov" data-tit></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form>
					<div class="mb-3">
						<label for="recipient-name" class="col-form-label" data-OS="">Número da OS:</label>
						<input type="text" class="form-control" id="recipient-name">
					</div>
					<div class="mb-3">
						<label for="message-text" class="col-form-label">Message:</label>
						<textarea class="form-control" id="message-text"></textarea>
					</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Send message</button>
				</div>
				</div>
			</div>
		</div>
	</body>
	<script>
		$(document).ready(function(){
			funIniciaTimeGrid();
			$("#inpDataFiltro").datepicker();
			var minDate = (<?php $config = new Config(); echo $config->diaApontRetroativo; ?> == true);
			js_DataApontRetroativo(minDate); //define minDate no calendário			
			$("#inpDataFiltro").datepicker("setDate", new Date().toLocaleDateString('en-ZA'));
			
			//verifica se colaborador pode aprovar para liberar a seleção de nomes
			(<?php if (isset($_SESSION["APV"])){ echo $_SESSION["APV"];}else{ echo 0;} ?> == true)?
				js_recuperaNomeIDSup(document.getElementById("selNome")):
				js_pesquisaGerenciar(new Date().toLocaleDateString('en-ZA'), document.getElementById("selNome").value, false);

			//chama função no js que carrega os campos conforme permissão selecionada via PHP
    		<?php
    		  echo ($readyPermissoes);
    		?>
		});	
	</script>
</html> 