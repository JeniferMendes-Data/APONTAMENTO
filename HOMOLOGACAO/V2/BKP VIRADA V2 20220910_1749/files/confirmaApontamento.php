<?php


	include('valida.php');
		if (empty($_SESSION['chapa']) ){
		session_destroy();
		header("Location: ../index.php");
		session_destroy();
		exit;
		  }
	session_start();


?>




<!doctype html>
<html lang="pt-br">


	<head>


		<!-- APONTAMENTO TELA CONFIRMACAO GERENTE APONTAMENTO HORA -->
		<title>APONTAMENTO</title>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/style.css">


	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.1/css/bootstrap-datepicker.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.1/js/bootstrap-datepicker.min.js"></script>



	<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

	<script src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


	<!--	<script src="../js/jquery.min.js"></script>	-->
		<script src="../js/popper.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/main.js"></script>
		<script src="../js/moment.min.js"></script>

	</head>
<body>


		<div class="wrapper d-flex align-items-stretch">
			<!-- menu Content  -->


			<!-- Page Content  -->
			<div id="content" class="p-4 p-md-5 pt-5">
				<!-- Main -->
				<div id="main" height="100vh">

				<!-- DSS -->
				<!--<div class="form-row" align="center"><div class="col-sm-12"><img src="<?php echo $site . "/img/hora_50.png"; ?>" alt="" /></span></div></div>-->

				<form action="confirmaApontamento.php" method="POST" id="confirmaApontamento" class="needs-validation">


					<br></br>


					<div class="form-row">
						<div class="col-sm-4">
							<label for="OS_APONTGER">OS:</label>
							<div class="input-group has-warning has-feedback">

								<input type="number" onKeyPress="if(this.value.length==6) return false;"   class="form-control"  name="OS_APONTGER" id="OS_APONTGER" required />

							</div>

						</div>

						<div class="col-sm-4">
							<label for="CHAPA_APONTGER">CHAPA:</label>
							<div class="input-group has-warning has-feedback">

								<input type="number" onKeyPress="if(this.value.length==6) return false;" class="form-control" name="CHAPA_APONTGER" id="CHAPA_APONTGER"required />

							</div>

						</div>

						<div class="col-sm-4">
							<label for="DATA_APONTGER">DATA:</label>
							<div class="input-group has-warning has-feedback">

								<input type="text" class="form-control" name="DATA_APONTGER" id="DATA_APONTGER" readonly required /></td>

							</div>

						</div>
					</div>
					<div class="form-row">
						<div class="col-sm-4">
							<label for="NOME_APONTGER">NOME:</label>
							<div class="input-group has-warning has-feedback">

								<input type="text" class="form-control" name="NOME_APONTGER" id="NOME_APONTGER" readonly />

							</div>

						</div>

						<div class="col-sm-4">
							<label for="CHAPA_APONTG">SEÇÃO:</label>
							<div class="input-group has-warning has-feedback">

								<input type="text" class="form-control " name="SECAO_APONTGER" id="SECAO_APONTGER" readonly />

							</div>

						</div>

						<div class="col-sm-4">
							<label for="DESC_APONTGER">DESCRIÇÃO:</label>
							<div class="input-group has-warning has-feedback">

								<input type="text" class="form-control" name="DESC_APONTGER"   id="DESC_APONTGER" readonly />

							</div>

						</div>
					</div>
					<div class="form-row">
						<div class="col-sm-4">
							<label for="H_INI_APONTGER">HORA INICIAL:</label>
							<div class="input-group has-warning has-feedback">

								<input type="time" class="form-control" name="H_INI_APONTGER" id="H_INI_APONTGER" required />

							</div>

						</div>

						<div class="col-sm-4">
							<label for="H_FIM_APONTGER">HORA FINAL:</label>
							<div class="input-group has-warning has-feedback">

								<input type="time" class="form-control" onblur="totalHora();" name="H_FIM_APONTGER" id="H_FIM_APONTGER" required />

							</div>

						</div>

						<div class="col-sm-4">
							<label for="H_TOTAL_APONTGER">TOTAL DE HORAS:</label>
							<div class="input-group has-warning has-feedback">

								<input type="text" class="form-control " name="H_TOTAL_APONTGER" id="H_TOTAL_APONTGER"   readonly />

							</div>

						</div>
					</div>
					<br>
					<div class="form-row" align="center"></br></div>
					<div class="form-row">
						<div class="col-sm-2 form-check">
							<label class="form-check-label align-center" for="RETRABALHO">
								RETRABALHO:
							  </label>
							  <input type="checkbox"  class="form-check-input-reverse" id="RETRABALHO" name="RETRABALHO"  value= "" onchange="check()"/>
						</div>
						<div id="CAUSARETRABALHO" class="col-sm-4 mr-2" style="display:none" >
    						<select id="INPUTCAUSARETRABALHO" name="INPUTCAUSARETRABALHO" class="form-select input-group-text col-md-12"  required>
    						  <option value="NA" selected>SELECIONE</option>
    						  <option value="FI">FORNECEDOR INTERNO</option>
    						  <option value="FE">FORNECEDOR EXTERNO</option>
    						  <option value="CI">CLIENTE INTERNO</option>
    						  <option value="PL">PLANEJAMENTO</option>
    						  <option value="FO">FALHA OPERACIONAL</option>
    						  <option value="FS">FALHA DE SUPERVISÃO</option>
    						  <option value="FC">FALHA COMERCIAL</option>
    						  <option value="RT">REPROVADO NO TESTE</option>
    						</select>
						</div>
						<div class="col-sm-5 form-check">
    						<label class="form-check-label" for="SERVCAMPO">SERVIÇO DE CAMPO:</label>
    						<input type="checkbox"  class="form-check-input-reverse" id="SERVCAMPO" name="SERVCAMPO"  value= "" onchange="servCampo()"/>
						</div>
					</div>
					<br>

					<div class="form-row" align="right">
						<div class="col-sm-12 form-group has-warning has-feedback" >

							<button   name="plus" id="plus" value='plus'class="btn btn-info">Enviar Apontamento</button> <!--<i class="fa fa-plus btn btn-info" aria-hidden="true"></i>-->
							<input type="button" name= "gerirHora" class="btn btn-primary" onclick="gerenciamento()" tabindex="5" value="Gerenciar Apontamento" />
						</div>
					</div>



				</div>
			</form>
			<?php
			session_start();
			
			if(isset($_SESSION['msg'])){
					echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalSucsses').modal('show'); });</script>
				<div class='modal fade' id='modalSucsses' tabindex='-1' role='dialog' aria-labelledby='modalSucsses' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='modalSucsses'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
						<div id='modalBorder'>
						  <div id='modalBorder' class='modal-body' >
							<div class='row'>
								<div class='col-md-12' style='text-align:center'>
								Validação executada com sucesso!
								</div>

							</div>
						  <div class='modal-footer'>
							<button type='button' class='btn btn-primary' data-dismiss='modal'>Fechar</button>
						  </div>
						</div>
						</div>
					  </div>
					</div>
				 </div>";
					unset($_SESSION['msg']);

				}elseif(!isset($_SESSION['msg'])){
				 if(isset($_SESSION['msg2'])){
						echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalSucsses').modal('show'); });</script>
				<div class='modal fade' id='modalSucsses' tabindex='-1' role='dialog' aria-labelledby='modalSucsses' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='modalSucsses'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
						<div id='modalBorder'>
						  <div id='modalBorder' class='modal-body' >
							<div class='row'>
								<div class='col-md-12' style='text-align:center'>
								Não foram localizados dados para validação de apontamento para os filtros informados.
								</div>

							</div>
						  <div class='modal-footer'>
							<button type='button' class='btn btn-primary' data-dismiss='modal'>Fechar</button>
						  </div>
						</div>
						</div>
					  </div>
					</div>
				 </div>";
				 unset($_SESSION['ms2']);
				 header("Location:gerencia.php");
					 }
				}

			?>

			<div class='modal fade' id='modalNoOS' tabindex='-1' role='dialog' aria-labelledby='modalNoOS' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='modalNoOSTitle'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
						<div id='modalBorder'>
						  <div id='modalBorder' class='modal-body' >
							<div class='row'>
								<div class='col-md-12' style='text-align:center'>
								Não foi encontrada OS com o valor informado, favor verificar se foi digitado corretamente.
								</div>

							</div>
						  <div class='modal-footer'>
							<button type='button' class='btn btn-primary' data-dismiss='modal'>Fechar</button>
						  </div>
						</div>
						</div>
					  </div>
					</div>
				 </div>

				 <!--Modal CHAPA-->

				 <div class='modal fade' id='modalNoCHAPA' tabindex='-1' role='dialog' aria-labelledby='modalNoCHAPA' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='modalNoCHAPA'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
						<div id='modalBorder'>
						  <div id='modalBorder' class='modal-body' >
							<div class='row'>
								<div class='col-md-12' style='text-align:center'>
								Não foi encontrado funcionário com a CHAPA informada, favor verificar se foi digitado corretamente.
								</div>

							</div>
						  <div class='modal-footer'>
							<button type='button' class='btn btn-primary' data-dismiss='modal'>Fechar</button>
						  </div>
						</div>
						</div>
					  </div>
					</div>
				 </div>


		 <?php
	session_start();
	if(isset($_POST['plus'])){

	$nos = $_POST['OS_APONTGER'];
	$descr = $_POST['DESC_APONTGER'];
	$data = $_POST['DATA_APONTGER'];
	$hini = $_POST['H_INI_APONTGER'];
	$hfim = $_POST['H_FIM_APONTGER'];
	$chapa = $_POST['CHAPA_APONTGER'];
	$nome = $_POST['NOME_APONTGER'];
	$retrabalho = $_POST ['RETRABALHO'];
	$servCampo = $_POST ['SERVCAMPO'];
	$causaRetrabalho = $_POST['INPUTCAUSARETRABALHO'];



	$_SESSION['servCampo']= $servCampo;
	$_SESSION['retrabalho']= $retrabalho;
	$_SESSION['causaRetrabalho']= $causaRetrabalho;
	$_SESSION['nos'] = $nos;
    $_SESSION['desc'] = $descr;
	$_SESSION['data'] = $data;
	$_SESSION['hini'] = $hini;
	$_SESSION['hfim'] = $hfim;
	$_SESSION['chapa'] = $chapa;
	$_SESSION['nome'] = $nome;
	


	echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalDispon').modal('show'); });</script>
					<div class='modal fade' id='modalDispon' tabindex='-1' role='dialog' aria-labelledby='modalDispon' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='modalDispon'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
				<div id='modalBorder'>
					 <div id='modalBorder' class='modal-body'>
						<div class = 'row'>
							<div class='col-md-12'>Deseja confirmar apontamento para OS informada?</div>
						</div>
						</br>
							<div class='row'>
								<div class='col-md-6'>	<strong>OS:</strong>{$nos}</div>
							</div>
							<div class='row'>
								<div class='col-md-12'>	<strong>Nome:</strong>{$nome}</div>
							</div>
							</br>
							<div class='row'>
								<div class='col-md-6'><strong>Descrição:</strong> {$descr}</div>
								<div class='col-md-6'><strong>Retrabalho:</strong> {$causaRetrabalho}</div>
							</div>
							<div class='row'>
								<div class='col-md-4'><strong>Data:</strong> {$data}</div>
								<div class='col-md-4'><strong>Hora I:</strong>{$hini}</div>
								<div class='col-md-4'><strong> Hora F: </strong>{$hfim}</div>
							</div>

						<div class='modal-footer'>
							<button type='button' class='btn btn-primary' data-dismiss='modal'>Cancelar</button>
							<button type='button' class='btn btn-info' onclick='enviaRM()' data-dismiss='modal' >Confirmar</button>
						  </div>
						</div>
						</div>
					  </div>
					</div>
				 </div>";

	}



				 ?>




		</div>
	</div>
</div>




 <script>
 function check(){

	if (document.getElementById('RETRABALHO').checked == true )
  	{
		$("#RETRABALHO").val('S');
		$("#CAUSARETRABALHO").show();

	} else if (document.getElementById('RETRABALHO').checked ==  false )
	{
		$("#INPUTCAUSARETRABALHO").val('NA')
		$("#CAUSARETRABALHO").hide();
	}


}

function  totalHora(){



	var startDate = $("#H_INI_APONTGER").val();
	var endDate = $("#H_FIM_APONTGER").val();
	var valor = moment
					.duration(moment(endDate,'HH:mm')
					.diff(moment(startDate,'HH:mm'))
					).asHours();
	var minutos = valor * 60;
	var horas = Math.trunc(minutos/60);
	minutos = ((valor - horas)*60)
	if (horas < 10){
		horas = '0' + horas
		}else {
		horas
		}
	if (minutos < 10){
		minutos = '0' + minutos
		}else {0
		minutos
		}
	var hora_formatada = horas + ':' + minutos;
	hora_formatada = hora_formatada.substring(0,5);

	$("#H_TOTAL_APONTGER").val(hora_formatada);


	if(hora_formatada.match(/0-.*/)){
		$('#plus').hide();
		$('#plus').prop('disabled', true);

	}else{
		$('#plus').show();
		$('#plus').prop('disabled', false);
	}

	if($("#DATA_APONTGER").val() > dataAtual){
		$('#plus').hide();
		$('#plus').prop('disabled', true);

	}


}
</script>
<script>
function servCampo(){

	if (document.getElementById('SERVCAMPO').checked == true )
  	{
		$("#SERVCAMPO").val('S')

	} else if (document.getElementById('SERVCAMPO').checked ==  false )
	{
		$("#SERVCAMPO").val('N')
	}


}

</script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#OS_APONTGER").blur(function(){

			$.ajax({
				url: "verificaDado.php",
				type:'GET',
				data: {numero_os: $(this).val()},
				dataType: 'json',
				success: function(response) {

					if(response[0].COD_RETORNO){
						$("#DESC_APONTGER").val(response[0].TMOVCOMPL_DESCRICAOCOMP),
						$('#plus').show();
					} else {
						$('#modalNoOS').modal('show');
						$('#plus').hide();
					}

				},

				error: function(xhr) {
				}
			});

		});

	});


	$(document).ready(function(){
		$("#CHAPA_APONTGER").blur(function(){

			$.ajax({
				url: "verificaNome.php",
				type:'GET',
				data: {chapa: $(this).val()},
				dataType: 'json',
				success: function(response) {

					$("#SECAO_APONTGER").val(response[0].PFUNC_CODSECAO),
					$("#NOME_APONTGER").val(response[0].PPESSOA_NOME)

				},
				error: function(xhr) {

				}
			});

		});
	});


</script>

<script type="text/javascript">
 $(function() {
	//comentar linha abaixo para bloquear o período
	//maiorDia = moment().format('DD');
	//comentar linha abaixo para liberar o período
	maiorDia = 5;
	diaAtual = moment().format('DD');
    var minimo = 5;


  if(maiorDia > minimo ){
	$( "#DATA_APONTGER" ).datepicker({
	changeMonth: false,
    changeYear: false,
	minDate:-(maiorDia-1),
	maxDate: new Date(),
	dateFormat: 'dd/mm/yy',
	dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
	dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
	dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
	monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
	monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
	nextText: 'Proximo',
	prevText: 'Anterior'
  });
  }else{

	$( "#DATA_APONTGER" ).datepicker({  selectOtherMonths: true,
	//minDate:"-1m -"+(maiorDia-1)+"d",
	minDate:"-1m -"+(diaAtual)+"d",
	maxDate: new Date(),
	dateFormat: 'dd/mm/yy',
    dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
    nextText: 'Proximo',
    prevText: 'Anterior'
   })
  };




 });
</script>



<script>
function gerenciamento(){
  confirmaApontamento.action = 'gerencia.php';
  confirmaApontamento.submit();
  }
 </script>

 <script>
 function enviaRM(){
  confirmaApontamento.action = '../xml/gerar_xml_confir.php';
  confirmaApontamento.submit();



}
</script>






</body>
</html>