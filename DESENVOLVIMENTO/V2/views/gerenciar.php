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
    	?>
		<div class="container">
			<div class="row align-items-end">
				<div class="col-md-5">
					<div class="form-group">
						<label for="inpDataFiltro">Data:</label>
						<input type="text" class="form-control" name="inpDataFiltro" id="inpDataFiltro">
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<label for="inpNome">Nome:</label>
						<input type="text" class="form-control" name="inpNome" id="inpNome">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<button type="button" name="" id="" class="btn bg text-light flex-row"><span class="material-icons">search</span></button>
					</div>
				</div>
			</div>
			<div class="row mt-2">
				<div class="accordion" id="accordionExample">
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingOne">
						<button class="accordion-button collapsed bg text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
						Calderaria
						</button>
						</h2>
						<div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
						<div class="accordion-body">
							<strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
						</div>
						</div>
					</div>
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingTwo">
						<button class="accordion-button collapsed bg text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							Carpintaria
						</button>
						</h2>
						<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
						<div class="accordion-body">
							<strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
						</div>
						</div>
					</div>
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingThree">
						<button class="accordion-button collapsed bg text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
							Departamento de Tecnologia
						</button>
						</h2>
						<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
						<div class="accordion-body">
							<strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
						</div>
						</div>
					</div>
					</div>
			</div>
			<div class="mt-2" id="divApontTime">			  
			</div>
		</div>
	</body>
	<script>
		$(document).ready(function(){
			var apontamentos = [
				{"id":"1234",title:"OS: 014595 - PARTE/PEÇA: BOBINA - ATIV: BOBINAR - RETRABALHO: SIM - SERVIÇO DE CAMPO: SIM", "start":"2022-06-29T08:10:00", "end":"2022-06-29T08:45:00", "url": "http://apontamentolocal/views/apontar.php/"},
				{groupId: '014595', "title":"OS: 014595 - PARTE/PEÇA: BOBINA - ATIV: BOBINAR - RETRABALHO: SIM - SERVIÇO DE CAMPO: SIM", "start":"2022-06-29T08:46:00", "end":"2022-06-29T09:58:00", "url": "http://apontamentolocal/views/apontar.php/"},
				{groupId: '014595', "title":"OS: 014595 - PARTE/PEÇA: BOBINA - ATIV: BOBINAR - RETRABALHO: SIM - SERVIÇO DE CAMPO: SIM", "start":"2022-06-29T13:00:00", "end":"2022-06-29T15:00:00"},
				{groupId: '014595', "title":"OS: 014595 - PARTE/PEÇA: BOBINA - ATIV: BOBINAR - RETRABALHO: SIM - SERVIÇO DE CAMPO: SIM", "start":"2022-06-29T10:00:00", "end":"2022-06-29T12:00:00"},
				{groupId: '014595', "title":"OS: 014595 - PARTE/PEÇA: BOBINA - ATIV: BOBINAR - RETRABALHO: SIM - SERVIÇO DE CAMPO: SIM", "start":"2022-06-29T07:00:00", "end":"2022-06-29T08:00:00", "html": '<i>some html</i>' }
			];
			$("#inpDataFiltro").datepicker();
			funIniciaTimeGrid(apontamentos);			
		});	
	</script>
</html> 