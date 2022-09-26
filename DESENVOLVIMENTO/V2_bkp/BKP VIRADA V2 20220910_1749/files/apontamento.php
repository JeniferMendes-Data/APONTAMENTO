
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



<?php

require "../sql/conectaDss.php";

$sql = "select SUBSTRING(TMOV_NUMEROMOV,4,9)TMOV_NUMEROMOV, TMOVCOMPL_DESCRICAOCOMP  from VIEW_MODAL_OS WHERE TMOV_NUMEROMOV IN ( '000000001', '000000002', '000000003', '000000004', '000000005', '000000006', '000000007', '000000008', '000000009', '000000011', '000000012', '000000013', '000000014', '000000015', '000000016', '000000017', '000000018', '000000019', '000000020', '000000021', '000000022', '000000023', '000000024', '000000025', '000000026', '000000027', '000000028', '000000029', '000000030', '000000031', '000000032', '000000033', '000000045', '000000046', '000000047', '000000048', '000000049')";

$stmt = sqlsrv_query($conn, $sql);

?>


<!doctype html>
<html lang="pt-br">
	<head>
		<?php require ('../config/config.php');?>
		<!-- APONTAMENTO INDEX-->
		<title>APONTAMENTO | LANÇAMENTO</title>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/style.css">
		<style>
		.right
   			{
   			float:right;

   			}
			@media screen and (max-width: 767px) {
            .select2 {
            width: 100% !important;
            }
         }
		</style>

		<a  class = "right" href="logout.php"><i class="fa fa-sign-out" style="font-size:36px; color:black"></i></a>

	</head>
	<body>



	<!--teste modal-->

	<script src="../js/jquery.min.js"></script>
	<script src="../js/popper.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/main.js"></script>

<?php



	if(isset($_POST['apontar'])){

	$nos = $_POST['NUM_OS'];

	$date=date_create("");
	$data=date_format($date,"d/m/y ");


	$hini = $_POST['H_INICIO'];
	$hfim = $_POST['H_FIM'];
	$retrabalho = $_POST ['RETRABALHO'];
	$servCampo = $_POST ['SERVCAMPO'];
	$causaRetrabalho = $_POST['INPUTCAUSARETRABALHO'];


	$_SESSION['horainicio'] = $hini;
	$_SESSION['horafim'] = $hfim;
	$_SESSION['n_nos']= $numOs;

	$_SESSION['retrabalho']= $retrabalho;
	$_SESSION['servCampo']= $servCampo;
	$_SESSION['causaRetrabalho']= $causaRetrabalho;


	if(strlen($nos) == 5 ){
		$numOs = '0000' . $nos;
		}elseif(strlen($nos) == 6){
		$numOs= '000'. $nos;
		}elseif($nos <= '000033'){
		$numOs= '000'. $nos;
		}



	$_SESSION['n_nos']= $numOs;


	$queryModal = "select * from VIEW_MODAL_OS where TMOV_NUMEROMOV = '{$numOs}'";


	$resultado = sqlsrv_query($conn, $queryModal);
	$result = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);

	$_SESSION['nos'] = $result['TMOV_NUMEROMOV'];
    $_SESSION['desc'] = $result['TMOVCOMPL_DESCRICAOCOMP'];
	$_SESSION['status'] = $result['TMOVCOMPL_STATUSOSCOMPL_DESC'];
	$_SESSION['statuscod'] = $result['TMOVCOMPL_STATUSOSCOMPL'];
	$_SESSION['departamento'] = $result['GCCUSTO_NOME'];




	if($nos <= '000033'){
		$nosDiversa =  '000' . $nos;

		if($_SESSION['horainicio']== '23:59' || $_SESSION['horafim']=='00:00' ){
			echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalHoIndispo').modal('show'); });</script>
			<div class='modal fade' id='modalHoIndispo' tabindex='-1' role='dialog' aria-labelledby='modalHoIndispo' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='modalHoIndispo'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
						<div id='modalBorder'>
						  <div id='modalBorder' class='modal-body'>
							<div class = 'row'>
								<div class= 'col-md-12'>
									Apontamento indisponível para OS informada
								</div>
							</div>
							<div class='row'>
								<div class='col-md-12'>
									Motivo: Horário indisponível.
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

		}else if($nosDiversa == '000000000'){
		    echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalHoIndispo').modal('show'); });</script>
		      <div class='modal fade' id='modalHoIndispo' tabindex='-1' role='dialog' aria-labelledby='modalHoIndispo' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='modalHoIndispo'>Atenção!</h5>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
							 <span aria-hidden='true'>&times;</span>
							</button>
						</div>
						<div id='modalBorder'>
                            <div id='modalBorder' class='modal-body'>
                                <div class = 'row'>
                                    <div class= 'col-md-12'>
									   Apontamento indisponível para OS informada
								    </div>
                                </div>
    							<div class='row'>
    								<div class='col-md-12'>
    									Motivo: OS INEXISTENTE.
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
		}
	}

	if(is_null($result)){
		if($nos > '000033') {
		echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalOSines').modal('show'); });</script>
		 <div class='modal fade' id='modalOSines' tabindex='-1' role='dialog' aria-labelledby='modalOSines' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='modalOSines'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
						<div id='modalBorder'>
						  <div id='modalBorder' class='modal-body'>
							<div class = 'row'>
								<div class= 'col-md-12'>
									Apontamento indisponível para OS informada
								</div>
							</div>
							<div class='row'>
								<div class='col-md-12'>
									Motivo: OS inexistente.
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
		}
	}else if(isset($result)){

		$status = $_SESSION['status'];
		$statuscod = $_SESSION['statuscod'];


      if($statuscod == '10' || $statuscod == '12' || $statuscod == '13' || $statuscod == '14' || $statuscod == '16' || $statuscod == '20'|| $statuscod == '21' || $statuscod == '22'){
	    echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalBloq').modal('show'); });</script>
		 <div class='modal fade' id='modalBloq' tabindex='-1' role='dialog' aria-labelledby='modalBloq' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='modalBloq'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
					<div id='modalBorder'>
						 <div id='modalBorder' class='modal-body'>
						<div class = 'row'>
							<div class= 'col-md-12'>
								Apontamento indisponível para OS informada!
								Motivo: Status bloqueado para apontamento.
							</div>
						</div>
							<div class='row'>
								<div class='col-md-6'><strong>OS:</strong>  {$_SESSION['nos']}</div>
								<div class='col-md-6>'<strong>Departamento:</strong>  {$_SESSION['departamento']}</div>
							</div>
							<div='row'>
								<div class='col-md-12'><strong>Descrição:</strong>  {$_SESSION['desc']}</div>
								<div class='col-md-12'></br><strong>Status:</strong> {$_SESSION['status']}</div>
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
			}else if ($statuscod <> '10' || $statuscod <> '12' || $statuscod <> '13' || $statuscod <> '14' || $statuscod <> '16' || $statuscod <> '20'|| $statuscod <> '21' || $statuscod <> '22' ){
				if($nos > '000033' && $_SESSION['horainicio']== '23:59' || $_SESSION['horafim']=='00:00'){
					echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalHoIndispo').modal('show'); });</script>
			<div class='modal fade' id='modalHoIndispo' tabindex='-1' role='dialog' aria-labelledby='modalHoIndispo' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='modalHoIndispo'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
						<div id='modalBorder'>
						  <div id='modalBorder' class='modal-body'>
							<div class = 'row'>
								<div class= 'col-md-12'>
									Apontamento indisponível para OS informada
								</div>
							</div>
							<div class='row'>
								<div class='col-md-12'>
									Motivo: Horário indisponível.
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
				}else if ($_SESSION['horainicio'] >= $_SESSION['horafim']) {
				    echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalHoIndispo').modal('show'); });</script>
            	       <div class='modal fade' id='modalHoIndispo' tabindex='-1' role='dialog' aria-labelledby='modalHoIndispo' aria-hidden='true'>
            		      <div class='modal-dialog modal-dialog-centered' role='document'>
            			     <div class='modal-content'>
            				    <div class='modal-header'>
            					  <h5 class='modal-title' id='modalHoIndispo'>Atenção!</h5>
        						  <button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
        		                      <span aria-hidden='true'>&times;</span>
        		                  </button>
            					</div>
            					<div id='modalBorder'>
            					   <div id='modalBorder' class='modal-body'>
            						  <div class = 'row'>
            					           <div class= 'col-md-12'>
            									Apontamento indisponível para OS informada
            								</div>
                                       </div>
            						  <div class='row'>
            						      <div class='col-md-12'>
            									Motivo: Horário Final maior que Horário de Início.
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
				}else if($retrabalho <>'S' ||($retrabalho =='S' && $causaRetrabalho <>'NA')){
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
								<div class='col-md-6'>	<strong>OS:</strong>{$_SESSION['nos']}</div>
								<div class='col-md-6'><strong>Departamento:</strong> {$_SESSION['departamento']}</div>
							</div>
							</br>
							<div class='row'>
								<div class='col-md-6'><strong>Descrição:</strong> {$_SESSION['desc']}</div>
								<div class='col-md-6'><strong>Causa Retrabalho:</strong> {$causaRetrabalho}</div>
							</div>
							</br>
							<div class= 'row'>
								<div class='col-md-12'>	<strong>Status:</strong> {$_SESSION['status']}</div>
							</div>
							<br>
							<div class='row'>
								<div class='col-md-4'><strong>Data:</strong> {$data}</div>
								<div class='col-md-4'><strong>Hora I:</strong>{$hini}</div>
								<div class='col-md-4'><strong> Hora F: </strong>{$hfim}</div>
							</div>

						<div class='modal-footer'>
							<button type='button' class='btn btn-primary' data-dismiss='modal'>Cancelar</button>
							<button type='button' class='btn btn-info'onclick='enviaDSS()'>Confirmar</button>
						  </div>
						</div>
						</div>
					  </div>
					</div>
				 </div>";
	 			}
			}
			if($retrabalho =='S' && $causaRetrabalho =='NA'){
			echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalCausaRetrabalho').modal('show'); });</script>
			<div class='modal fade' id='modalCausaRetrabalho' tabindex='-1' role='dialog' aria-labelledby='modalCausaRetrabalho' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='modalCausaRetrabalho'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
						<div id='modalBorder'>
						  <div id='modalBorder' class='modal-body'>
							<div class = 'row'>
								<div class= 'col-md-12'>
									Favor informar a causa do retrabalho
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
				}
		}else{
		echo '<div class="form-control">Apontamento não foi lançado, favor tentar novamente ou entre em contato com o setor de T.I </div>';
		header("Location: apontamento.php");
		}
	}


?>


	<!--Fim teste modal-->
		<div class="wrapper d-flex align-items-stretch">
			<!-- menu Content  -->

			<!-- Page Content  -->
			<div id="content" class="p-4 p-md-5 pt-5">
				<!-- Main -->
				<div id="main" height="100vh">
				<!-- DSS -->
				<div class="form-row" align="center"><div class="col-sm-12"><img src="<?php echo $site . "/img/hora_200.png"; ?>" alt="" /></span></div></div>

				<form method="post" action="apontamento.php" id="apontamento" >


					<div class="form-row">
						<div class="col-sm-12">
							<label for="CHAPA">CHAPA:</label>
							<div class="input-group has-warning has-feedback">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-user"></i>
									</div>
								</div>
							<input type="Number" name="CHAPA" value="<?php echo $_SESSION['chapa'] ?>" size="6" maxlength="6" tabindex="1" class="form-control" onKeyPress="if(this.value.length==6) return false;" min="0" readonly />

							</div>

						</div>
					</div>
					<div class="form-row">
						<div class="col-sm-6">
							<label for="H_INICIO">H. INICIO:</label>
							<div class="input-group has-warning has-feedback">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-clock-o fa-spin"></i>
									</div>
								</div>
								<input type="Time" name="H_INICIO"  size="6" maxlength="6" tabindex="2" class="form-control" onkeyup="myFunction(this,this.value)" required />

							</div>

						</div>

						<div class="col-sm-6">
							<label for="H_FIM">H. FIM:</label>
							<div class="input-group has-warning has-feedback">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-clock-o fa-spin"></i>
									</div>
								</div>
								<input type="Time" name="H_FIM" size="6" maxlength="6" tabindex="3" class="form-control" onkeyup="myFunction(this,this.value)" required />

							</div>

						</div>
					</div>
					<div class="form-row">
						<div class="col-sm-12">
							<label for="NUM_OS">Nº OS:</label>
							<div class="input-group ">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-cog fa-spin"></i>
										</div>
									</div>

								<input type="Number" name="NUM_OS" id="NUM_OS" placeholder="Nº OS" onKeyPress="if(this.value.length==6) return false;" min="0" required  size="6" maxlength="6" tabindex="4" class="form-control" />

								<button type="button" style='border:none' onclick='osDiversas();'><i class="fa fa-list-ul" aria-hidden="true"></i></button>


							</div>

						</div>
					</div>
					<div class="form-row" align="center"></br></div>
					<div class="form-row">
						<div class="col-sm-2 form-check">
							<label class="form-check-label" for="RETRABALHO">
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
					<div class="form-row" align="center"></br></div>
					<div class="form-row">


					</div>
					</div>
					<div class="form-row" align="center"></br></div>
					<div class="form-row" align="center">
						<div class="col-sm-12" >
							<div class="form-group has-warning has-feedback" >
								<button  type="submit" name="apontar"  class="btn btn-primary" onclick="" tabindex="5" value="Apontar">Apontar</button>

							</div>
						</div>
					</div>


				 <div class="modal fade" id="ModalOS" tabindex="-1" role="dialog" aria-labelledby="ModalOS" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="ModalOS">Outras Atividades</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
											<span aria-hidden="true">&times;</span>

										</button>


								</div>
						<div id="modalBorder">
						  <div id="modalBorder" class="modal-body">
							<div class = "row">
								<select id="selectOs" size="6" maxlength="6" tabindex="4" class="js-example-basic-single  form-control col-md-12  " onKeyPress="if(this.value.length==6) return false;"   style="
										height: 400px!important;">
										<?php
										 while( $row =  sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
											echo "<option value=".$row['TMOV_NUMEROMOV'].">".$row['TMOV_NUMEROMOV']." - ".$row['TMOVCOMPL_DESCRICAOCOMP']."</option>";
										}

										?>
								</select>

							</div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-info" data-dismiss="modal">Confirmar</button>
						  </div>
						</div>
						</div>
					  </div>
					</div>
				 </div>
		 <?php
	session_start();

			if(isset($_SESSION['menssage'])){
				echo "<script language= 'JavaScript'>$(document).ready(function(){ $('#modalMsgSucsses').modal('show'); });</script>
				<div class='modal fade' id='modalMsgSucsses' tabindex='-1' role='dialog' aria-labelledby='modalMsgSucsses' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
								<h5 class='modal-title' id='modalMsgSucsses'>Atenção!</h5>
										<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
											<span aria-hidden='true'>&times;</span>
										</button>
								</div>
						<div id='modalBorder'>
						  <div id='modalBorder' class='modal-body' >
							<div class='row'>
								<div class='col-md-12' style='text-align:center'>
									Apontamento realizado com sucesso!
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
				unset($_SESSION['menssage']);
			}
	?>

				</form>

			</div>

	</div>
</div>


</body>

    <!-- SELECT 2-->

		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <!-- /SELECT2 -->

<script>
function enviaDSS(){
  apontamento.action = 'cadastraHoraDss.php';
  apontamento.submit();

}
</script>
<script>
function novoApontamento(){
  gerencia.action = 'confirmaApontamento.php';
  gerencia.submit();
}
</script>
<script>
function gereciar(){
  apontamento.action = 'gerencia.php';
  apontamento.submit();
}
</script>


<script>
function check(){

	if (document.getElementById('RETRABALHO').checked == true ){
		$("#RETRABALHO").val('S');
		$("#CAUSARETRABALHO").show();
		$("#INPUTCAUSARETRABALHO").val('NA');
	}else if (document.getElementById('RETRABALHO').checked ==  false ){
		$("#INPUTCAUSARETRABALHO").val('NA');
		$("#CAUSARETRABALHO").hide();
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

<script>
	function myFunction(x, y) {
	if (y.length == x.maxLength) {
	var next = x.tabIndex;
	if (next < document.getElementById("formBsucaOS").length) {
	document.getElementById("formBsucaOS").elements[next].focus();
	  }

	}
}
</script>

<script type="text/javascript">

	function osDiversas(id) {
    $('#ModalOS').modal('show');
}
	</script>

<script type="text/javascript">
$(document).on('click', 'option', function() {
 var value = $(this).val();
 $('#NUM_OS').val(value);
});

</script>




</html>
