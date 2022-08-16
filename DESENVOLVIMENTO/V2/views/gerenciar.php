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
		<div class="modal fade" id="divEditAprov" tabindex="-1" aria-labelledby="lblEditAprov" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="lblEditAprov" data-tit></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form>
					<div class="row mb-3">
						<div>
							<label for="inpSecao" class="col-sm-2 col-form-label col-form-label-sm">Seção:</label>
							<div class="col-sm-12">
								<input type="text" class="form-control col-form-label-sm" id="inpSecao" name="inpSecao" readOnly></input>
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<label for="inpNumOS" class="col-sm-1 col-form-label col-form-label-sm">OS:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control col-form-label-sm" id="inpNumOS" name="inpNumOS" readOnly></input>
						</div>
						<label for="inpDataApt" class="col-sm-1 col-form-label col-form-label-sm">Data:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control col-form-label-sm" id="inpDataApt" name="inpDataApt" readOnly></input>
						</div>
					</div>
					<div class="row mb-3 align-items-center">
						<label for="inpHraIni" class="col-sm-1 col-form-label col-form-label-sm">Hora Início:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control col-form-label-sm" id="inpHraIni" name="inpHraIni" readOnly></input>
						</div>
						<label for="inpHraFim" class="col-sm-1 col-form-label col-form-label-sm">Hora Fim:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control col-form-label-sm" id="inpHraFim" name="inpHraFim" readOnly></input>
						</div>
					</div>
					<div class="row mb-3 align-items-center">                       
						<label for="selParte" class="col-sm-2 col-form-label col-form-label-sm">Partes/Peças:</label>
						<div class="col-sm-10">
							<select id="selParte" name="selParte" class="selectpicker form-control border" data-live-search="true" title="Selecionar Parte/Peça" data-style="btn" disabled></select>
						</div>
					</div>
					<div class="row mb-3 align-items-center">
						<label for="selAtiv" class="col-sm-2 col-form-label col-form-label-sm">Atividade:</label>
						<div class="col-sm-10">
							<select id="selAtiv" name="selAtiv" class="selectpicker form-control border" data-live-search="true" title="Selecionar Atividade" data-style="btn" disabled></select>
						</div>
					</div>
					<div class="row mb-3 align-items-center">
						<label for="selCausaRetrabalho" class="col-sm-2 col-form-label col-form-label-sm">Retrabalho:</label>
						<div class="col-sm-10">
							<select id="selCausaRetrabalho" name="selCausaRetrabalho" class="selectpicker form-control border" data-live-search="true" title="Selecionar Causa do Retrabalho" data-style="btn" disabled></select>
						</div>
					</div>
					<div class="row mb-3 align-items-center">						
						<div class="col-sm-1">
							<input id="inpServCampo" name="inpServCampo" type="checkbox" class="form-check-input-inline mr-1" disabled></input>
						</div>
						<label for="inpServCampo" class="col-sm-11 col-form-label col-form-label-sm">Serviço de Campo</label>
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
			$('#divCarregando').show(); //exibe gif de carregamento no calendário
			//verifica se colaborador pode aprovar
			var sup = <?php if (isset($_SESSION["APV"])){ echo $_SESSION["APV"];}else{ echo 0;} ?>;
			funIniciaTimeGrid(sup);
			$("#inpDataFiltro").datepicker();
			var minDate = (<?php $config = new Config(); echo $config->diaApontRetroativo; ?> == true);
			js_DataApontRetroativo(minDate); //define minDate no calendário			
			$("#inpDataFiltro").datepicker("setDate", new Date().toLocaleDateString('en-ZA'));
			
			//libera a seleção de nomes
			(sup == true)?
				js_recuperaNomeIDSup(document.getElementById("selNome")):
				js_pesquisaGerenciar(new Date().toLocaleDateString('en-ZA'), document.getElementById("selNome").value,document.getElementById("selAcao").value, false);
			
			//retorna as causas do retrabalho
            document.getElementById('selCausaRetrabalho').innerHTML = '<?php echo include_causaRetrabalho(); ?>';
			$("#selCausaRetrabalho").selectpicker("refresh");
			
			//chama função no js que carrega os campos conforme permissão selecionada via PHP
    		<?php
    		  echo ($readyPermissoes);
    		?>
		});	
	</script>
</html> 