<!doctype html>
<html lang="pt-br">
	
	
	<head>
		<?php 
		
		include('valida.php');
		if (empty($_SESSION['chapa']) ){        
		session_destroy();
		header("Location: ../index.php");
		session_destroy();
		exit;
		  } 
		
		
		session_start();
	


		require ('../config/config.php');?>
		
		<!-- APONTAMENTO DECISAO-->
		<title>APONTAMENTO | DECISÃO</title>
		
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
				<!--<div class="form-row" align="center"><div class="col-sm-12"><img src="<?php echo $site . "img/hora_200.png"; ?>" alt="" /></span></div></div>-->			
				
				
				<form method="post" action="#" id="decisao" class="needs-validation">					
				
					<br></br>
					<br></br>
					<br></br>
					<br></br>
					<br></br>
					
					<div class="form-row" align="center"></br></div>
					<div class="form-row" align="center">
							<div class="col-sm-12 form-group has-warning has-feedback" >							
								<input type="submit" name= "lancarHora" class="btn btn-primary" style="background-color:#807a7a" onclick="apontamento();" tabindex="5" value="Apontamento Hora" />
								<input type="submit" name= "gerirHora" class="btn btn-primary" tabindex="5" onclick="gerenciamento();" value="Gerenciar Apontamento" />
							
							</div>
					</div>

					
				
				</form>
	
								

			</div>
		</div>
	</div>
<script> 
	
function gerenciamento(){
	decisao.action = 'gerencia.php'; 
	decisao.submit();
  }
  
  function apontamento(){
	decisao.action = 'apontamento.php'; 
	decisao.submit();
  }
  
</script>
	
	<script src="../js/jquery.min.js"></script>
	<script src="../js/popper.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/main.js"></script>
</body>
</html>