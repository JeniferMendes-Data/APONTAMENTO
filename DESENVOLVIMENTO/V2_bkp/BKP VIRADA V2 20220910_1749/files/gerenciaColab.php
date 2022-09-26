<?php 

include('valida.php');
if (empty($_SESSION['chapa']) ){        
session_destroy();
header("Location: ../index.php");
session_destroy();
exit;
  } 


	session_start(); 
	require "../sql/conectaDss.php";
	
	if(isset($_POST['filtrar'])){
		
		$usuario = $_SESSION['chapa'];	
		$os = $_POST['OS_GERENCIA'];		
		$h_ini = $_POST['PERIODO_GERENCIA'];
		$h_fim = $_POST['PERIODO2_GERENCIA'];
		$cliente= $_POST['CLIENTE_GERENCIA'];
		$status= $_POST['STATUS_GERENCIA'];
		
	

		
		$filtro_os = "";		
		$filtro_h_ini = "";
		$filtro_h_fim = "";
		$filtro_cliente="";
		$filtro_status="";
		
		
		
		
		
		
		if(!empty($os)){
			$filtro_os = "AND OS = " . $os;
		}
		
	
		if(!empty($cliente)){
			$filtro_cliente = "AND CLIENTE LIKE " . "'%{$cliente}%'";
		}
		
		if(!empty($h_ini)){
		
		 $filtro_h_ini = "AND CONVERT(varchar, H_INICIO, 23) BETWEEN" . "'{$h_ini}'" . " AND " . "'{$h_fim}'";
				
		}
		if(!empty($status)){
			$filtro_status = "AND VALIDA = " . "'$status'" ;
		}
		
		
	
		
	$sql = "select * FROM VIEW_LANCAMENTOS_HIST_OS  WHERE   CHAPA IN('{$usuario}')  $filtro_os $filtro_chapa $filtro_nome $filtro_secao $filtro_cliente $filtro_h_ini $filtro_status $filtro_equipe";


	$stmt = sqlsrv_query( $conn, $sql);

	}
	
	
	
	

?>


<!doctype html>
<html  lang="pt_br">
	
	
	<head>
		<?php require ('../config/config.php');	?>
		
		<!-- APONTAMENTO GERENCIAMENTO SUPERV HORA -->
		<title>GERÊNCIA | COLABORADOR</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet"/>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>		
		<link rel="stylesheet" href="../css/style.css"/>	
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"/>
		<script src="../js/jquery.min.js"></script>
		<script src="../js/popper.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/main.js"></script>
		<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
		<script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>	
<style>
	
.right
{
float:right;

}




</style>
		
		<a  class = "right" href="logout.php"><i class="fa fa-sign-out" style="font-size:36px; color:black"></i></a>
		
		
		</head>
	
	<body>

		
		<div class="wrapper d-flex align-items-stretch">
			<!-- menu Content  -->
			
			
			
			
			<!-- Page Content  -->
			<div id="content" class="p-4 p-md-5 pt-5">
				<!-- Main -->
				<div id="main" height="100vh">
							
				<!-- DSS -->
				<!--<div class="form-row" align="center"><div class="col-sm-12"><img src="<?php echo $site . "/img/hora_200.png"; ?>" alt="" /></span></div></div>-->			
						
				<form method="post" id="gerenciaColab" class="needs-validation">					
				
									
				<div class="form-row" align="center"></br></div>
					<div class="form-row" align="left">											
							<div class="col-sm-4">
								<label for="OS_GERENCIA">OS:</label>
									<div class="input-group has-warning has-feedback">
										<div class="input-group-prepend">											
										</div>
										<input type="number" name="OS_GERENCIA"  size="6" maxlength="6" tabindex="3" class="form-control"   />								
									</div>
									<div class="invalid-feedback">
										OBRIGATÓRIO.
									</div>
							</div>							
							<div class="col-sm-8">
								<label for="CLIENTE_GERENCIA">CLIENTE:</label>
									<div class="input-group has-warning has-feedback">
										<div class="input-group-prepend">											
										</div>
										<input type="text" name="CLIENTE_GERENCIA"  tabindex="3" class="form-control" />								
									</div>
									<div class="invalid-feedback">
										OBRIGATÓRIO.
									</div>
							</div>
						</div>	
				
					
					<div class="form-row" align="left">											
							
							
							<div class="col-sm-4">
								<label for="PERIODO_GERENCIA">PERÍODO INICIAL:</label>
									<div class="input-group has-warning has-feedback">
										<div class="input-group-prepend">											
										</div>
										<input type="date" name="PERIODO_GERENCIA"  tabindex="3" class="form-control"   />								
									</div>
									<div class="invalid-feedback">
										OBRIGATÓRIO.
									</div>
							</div>
							
							<div class="col-sm-4">
								<label for="PERIODO2_GERENCIA">PERÍODO FINAL:</label>
									<div class="input-group has-warning has-feedback">
										<div class="input-group-prepend">											
										</div>
										<input type="date" name="PERIODO2_GERENCIA"  tabindex="3" class="form-control"   />								
									</div>
									<div class="invalid-feedback">
										OBRIGATÓRIO.
									</div>
							</div>	
							<div class="col-sm-4">
								<label for="STATUS_GERENCIA">STATUS:</label>
									<div class="input-group has-warning has-feedback">
										<div class="input-group-prepend">											
										</div>
										<select class="form-control" id="STATUS_GERENCIA" name="STATUS_GERENCIA">
											<option selected value="P">Pendente</option>
											<option  value="A">Aprovado</option>
											<option value="R">Reprovado</option>
										</select>							
									</div>
									<div class="invalid-feedback">
										OBRIGATÓRIO.
									</div>
							</div>	
					</div>
					<br></br>
						<div class="form-row" align="right">
								<div class="col-sm-12 form-group has-warning has-feedback" >							
									<button type="submit"   name= "filtrar" class="btn btn-primary" tabindex="5" value="Filtrar">Filtrar</button>
								</div>
					</form>
					
					
					
				</div>
				<form  action="apontamentoColab.php" id='gerenciaColabTable' method="POST">
					<div class="row" >
						<div class="col-sm-12">
							
							<table id="tabDashBoard"
							data-toggle="table"						
							data-checkbox-header="true"
							data-click-to-select="true"		   
	 
							class="table table-striped  " 
							style="width:100%" >
								<thead >
									<tr>
										<th>STATUS</th>	
										<th>RETRABALHO</th>
										<th>OS</th>		
										<th>DESCRIÇÃO</th> 									
										<th>HI</th>
										<th>HF</th>	
									</tr>								
									</thead>
								<tbody>
								
									<?php 
																	
										while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {																										
										 $secao = $row['SECAO_COD'];										
										 $dateI = $row['H_INICIO'];
										 $result = $dateI->format('d-m-Y H:i'); 
										 $dateF = $row['H_FIM'];
										 $resultF = $dateF->format('d-m-Y H:i'); 
											echo "<tr>
												<td>".$row['VALIDA']."</td>	
												<td>".$row['RETRABALHO']."</td>												
												<td>".$row['OS']."</td>												
												<td>".$row['DESCRICAO_OS']."</td>
												<td>".$result."</td>
												<td>".$resultF."</td>
												
											</tr>";
										}?>
								</tbody>
							</table>
						</div>
					</div>
					<br/>
					<div class="form-row" align="right">
						<div class="col-sm-12 form-group has-warning has-feedback" >							
							
							<input type="submit" name= "apontar" class="btn btn-primary"   tabindex="5" value="Apontamento" />
						</div>					
					</div>
				</form>
			</div>
		</div>
	</div>
	
<script>
	 $(document).ready(function() {
		 $('#tabDashBoard').DataTable({
				"language" : {
				"sEmptyTable": "Nenhum registro encontrado",
				"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
				"sInfoFiltered": "(Filtrados de _MAX_ registros)",
				"sInfoThousands": ".",
				"sLengthMenu": "_MENU_ resultados por página",
				"sLoadingRecords": "Carregando...",
				"sProcessing": "Processando...",
				"sZeroRecords": "Nenhum registro encontrado",
				"sSearch": "Pesquisar",
				"oPaginate": {
					"sNext": "Próximo",
					"sPrevious": "Anterior",
					"sFirst": "Primeiro",
					"sLast": "Último"
				},
				"oAria": {
					"sSortAscending": ": Ordenar colunas de forma ascendente",
					"sSortDescending": ": Ordenar colunas de forma descendente"
				},
				"select": {
					"rows": {
						"_": "Selecionado %d linhas",
						"0": "Nenhuma linha selecionada",
						"1": "Selecionado 1 linha"
					}
				},
				"buttons": {
					"copy": "Copiar para a área de transferência",
					"copyTitle": "Cópia bem sucedida",
					"copySuccess": {
						"1": "Uma linha copiada com sucesso",
						"_": "%d linhas copiadas com sucesso"
					}
				}
			}
		});
	});
</script>

	
<script> 
/*function apontar(){
  gerenciaColabTable.action = 'apontamentoColab.php'; 
  gerenciaColabTable.submit();
}*/
</script>


  

</body>
</html>