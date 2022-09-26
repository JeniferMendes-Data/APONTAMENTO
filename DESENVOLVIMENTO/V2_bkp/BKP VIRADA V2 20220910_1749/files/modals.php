<?php session_start();
	$nos = $_POST['NUM_OS'];
	
$server = "192.168.0.10";
$connectionInfo = array( "Database"=>"DSS", "UID"=>"sa", "PWD"=>"Arh#100121" );
$conn = sqlsrv_connect( $server, $connectionInfo );

$sql = "select * from VIEW_MODAL_OS where TMOV_NUMEROMOV = '{$nos}'";

$stmt = sqlsrv_query($conn, $sql);
$resultado = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

 if(isset($resultado)){ ?>
 <script>
        $(document).ready(function(){
            $("#modalOSines").modal();
        });
    </script>
	
	<?php
	 }
	?>
	
	
?>

<!doctype html>
<html lang="pt_br">
	
	
	<head>
		<?php require ('../config.php');?>

		<!-- APONTAMENTO INDEX-->
		<title>APONTAMENTO</title>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/style.css">
	</head>
	<body>

		
		<div class="wrapper d-flex align-items-stretch">
			<!-- menu Content  -->
			
		
			<!-- Page Content  -->
			<div id="content" class="p-4 p-md-5 pt-5">
				<!-- Main -->
				<div id="main" height="100vh">
				<!-- DSS -->
				<div class="form-row" align="center"><div class="col-sm-12"><img src="<?php echo $site . "/img/hora_200.png"; ?>" alt="" /></span></div></div>
				
				
				
				<form method="post" action="" id="login" class="needs-validation">
					
					
					
					<div class="form-row">
						<div class="col-sm-12">
							<label for="usuario">Usuário:</label>
							<div class="input-group has-warning has-feedback">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-user"></i>
									</div>
								</div>
								<input type="Number" name="usuario" id="usuario" placeholder="USUÁRIO" min="0" size="6" maxlength="6" tabindex="4" class="form-control" onkeyup="myFunction(this,this.value)" required />
								<div class="invalid-feedback">
									OBRIGATÓRIO.
								</div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-sm-12">
							<label for="senha">Senha:</label>
							<div class="input-group has-warning has-feedback">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-unlock-alt"></i>
									</div>
								</div>
								<input type="password" name="senha" placeholder="SENHA" min="0" size="6" maxlength="6" tabindex="4" class="form-control" onkeyup="myFunction(this,this.value)" required />
								<div class="invalid-feedback">
									OBRIGATÓRIO.
								</div>
							</div>
						</div>
					</div>
					<div class="form-row" align="center"></br></div>
					<div class="form-row" align="center">
						<div class="col-sm-12" >
							<div class="form-group has-warning has-feedback" >
								<input type="submit" name= "acessar" class="btn btn-primary" tabindex="5" value="Acessar"  />
							</div>
						</div>
					</div>

					<!--FUNÇÃO PARA PULAR PARA PRÓXIMO CAMPO-->
					<script>
						function myFunction(x, y) {
							if (y.length == x.maxLength) {
								var next = x.tabIndex;
								if (next < document.getElementById("login").length) {
									document.getElementById("login").elements[next].focus();
								}
							}
						}
					</script>
				</form>
				
			</div>
		</div>
	</div>
	 <div class="modal fade" id="modalOSines" tabindex="-1" role="dialog" aria-labelledby="ModalOS" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="modalOSines">Atenção!</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
											<span aria-hidden="true">&times;</span>
										</button>
								</div>
						<div id="modalBorder">		
						  <div id="modalBorder" class="modal-body">
							<div class = "row">
								<?php echo 'Os Inexistente'?>
								
							</div>
						  <div class="modal-footer">
						 							
							<button type="button" class="close" data-dismiss="modal">Fechar</button>
						  </div>
						</div>
						</div>	
				
					  </div>
					</div>
				 </div>
	
	
	<script>
	
	
	</script>
	

	<script src="../js/jquery.min.js"></script>
	<script src="../js/popper.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/main.js"></script>
</body>
</html>