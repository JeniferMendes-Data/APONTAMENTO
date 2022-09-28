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
		
	
		$supServCampo = $_SESSION['usuarioServCampo'];
		$supe = $_SESSION['usuarioNiveisAcessoId'];
		
	
		$os = $_POST['OS_GERENCIA'];
		$chapa= $_POST['CHAPA_GERENCIA'];
		$nome = $_POST['NOME_GERENCIA'];
		$secao = $_POST['SECAO_GERENCIA'];
		$h_ini = $_POST['PERIODO_GERENCIA'];
		$h_fim = $_POST['PERIODO2_GERENCIA'];
		$cliente= $_POST['CLIENTE_GERENCIA'];
		$status= $_POST['STATUS_GERENCIA'];
		$equipe= $_POST['EQUIPE'];
		$permission = '';

		if($status <> 'P'){
			$permission = ' disabled = "disabled"';
		}

		$filtro_chapa= "";
		$filtro_os = "";
		$filtro_nome = "";
		$filtro_secao = "";
		$filtro_h_ini = "";
		$filtro_h_fim = "";
		$filtro_cliente="";
		$filtro_status="";
		$filtro_equipe="";
		
		
		
		
		
		if(!empty($os)){
			$filtro_os = "AND OS LIKE " . "'%{$os}'";
		}
		
		if(!empty($chapa)){
			$filtro_chapa= " AND CHAPA = " . "'{$chapa}'";
		}
		
		if(!empty($nome)){
			$filtro_nome = "AND NOME LIKE " . "'%{$nome}%'"; 
		}
		
		if(!empty($secao)){
			$filtro_secao = "AND SECAO LIKE " . "'%{$secao}%'";
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
		if(!empty($equipe)){
			if($equipe <> 'NULL'){
			$filtro_equipe = "AND EQUIPE_COD = " . "$equipe" ;
			
			}
		}
		
	
		
	if($supServCampo == 'SERVCAMPOT' ){
		
	$sql = "select * FROM VIEW_LANCAMENTOS_HIST_OS  WHERE  ((SERV_CAMPO ='S' AND SECAO_COD IN({$supe})) or 	SECAO_COD = '01.02.021' )$filtro_os $filtro_chapa $filtro_nome $filtro_secao $filtro_cliente $filtro_h_ini $filtro_status $filtro_equipe";

	}elseif($supServCampo == 'SERVCAMPOM'){
		$sql = "select * FROM VIEW_LANCAMENTOS_HIST_OS  WHERE  ((SERV_CAMPO ='S' AND SECAO_COD IN({$supe})) or SECAO_COD = '01.01.029') $filtro_os $filtro_chapa $filtro_nome $filtro_secao $filtro_cliente $filtro_h_ini $filtro_status $filtro_equipe";
	}
	else{			
	$sql = "select * FROM VIEW_LANCAMENTOS_HIST_OS  WHERE  SERV_CAMPO <> 'S'  AND SECAO_COD IN({$supe})  $filtro_os $filtro_chapa $filtro_nome $filtro_secao $filtro_cliente $filtro_h_ini $filtro_status $filtro_equipe";
		
	}
 

	$stmt = sqlsrv_query( $conn, $sql);

	}
	
	
	
	

?>


<!doctype html>
<html  lang="pt_br">
	
	
	<head>
		<?php require ('../config/config.php');	?>
		
		<!-- APONTAMENTO GERENCIAMENTO SUPERV HORA -->
		<title>APONTAMENTO | GERÊNCIA</title>
		
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

<!--
input[type="checkbox"].onlinecheckbox {
    color: #ffffff;
}

input[type="checkbox"].onlinecheckbox:checked {
    color: #428bca;
}
<!--Mudar o like de cor inicio-->

<!--
.icone {
    position: relative;
    cursor: pointer;
}
.branco{
    position: relative;
    color: green;
}
.preto {
    position: absolute;
    left: 0;
    top: 0;
    color: transparent;
}
.icone:hover .preto {
    color: red;
}
.icone:hover .branco {
    color: transparent;
}

<!--Mudar o like de cor fim-->


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
						
				<form method="post" id="gerencia" class="needs-validation">					
				
									
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
								<label for="CHAPA_GERENCIA">CHAPA:</label>
									<div class="input-group has-warning has-feedback">
										<div class="input-group-prepend">											
										</div>
										<input type="number" name="CHAPA_GERENCIA" size="6" maxlength="6" tabindex="3" class="form-control"   />								
									</div>
									<div class="invalid-feedback">
										OBRIGATÓRIO.
									</div>
							</div>							
							<div class="col-sm-8">
								<label for="NOME_GERENCIA">NOME:</label>
									<div class="input-group has-warning has-feedback">
										<div class="input-group-prepend">											
										</div>
										<input type="text" name="NOME_GERENCIA"   tabindex="3" class="form-control"   />								
									</div>
									<div class="invalid-feedback">
										OBRIGATÓRIO.
									</div>
								</div>
							</div>
					
					<div class="form-row" align="left">											
							<div class="col-sm-2">
								<label for="SECAO_GERENCIA">SEÇÃO:</label>
									<div class="input-group has-warning has-feedback">
										<div class="input-group-prepend">											
										</div>
										<input type="text" name="SECAO_GERENCIA" tabindex="3" class="form-control"   />								
									</div>
									<div class="invalid-feedback">
										OBRIGATÓRIO.
									</div>
							</div>	
							<div class="col-sm-2">
								<label for="EQUIPE">EQUIPE:</label>
									<div class="input-group has-warning has-feedback">
										<div class="input-group-prepend">											
										</div>
										<select class="form-control" id="EQUIPE" name="EQUIPE">
											<option selected value="NULL">Selecione</option>
											<?php 
											
											require "../sql/conectaCorporeRM.php";
	
												$sql2 = "select CODINTERNO, DESCRICAO FROM PEQUIPE";
												$stmt2 = sqlsrv_query($conn2, $sql2);
																					
											while( $row2 =  sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {
											echo "<option value=".$row2['CODINTERNO'].">".$row2['DESCRICAO']."</option>";
											

										}
									
										?>
										</select>							
									</div>
									<div class="invalid-feedback">
										OBRIGATÓRIO.
									</div>
							</div>		
							<div class="col-sm-3">
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
							
							<div class="col-sm-3">
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
							<div class="col-sm-2">
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
						if(isset($_SESSION['ms2'])){
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
								Não existe apontamento selecionado, escolha ao menos um.
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
					
				</div>
				<form action="../xml/gerar_xml.php" method="POST">
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
										<th>CLIENTE</th>										
										<th>CHAPA</th>
										<th>NOME</th>
										<th>SEÇÃO</th>		                
										<th>DESCRIÇÃO</th> 									
										<th>HI</th>
										<th>HF</th>										
										<th><span ><i class="fa fa-thumbs-up"><input type="checkbox" class="checkbox selected chb" name="selectall" id="selectall"/></i></span></th>
										<th><span ><i class="fa fa-thumbs-down "><input type="checkbox" class="checkbox checkbox-danger chb" name="selectallrem"  id="selectallrem"/></i></span></th> 
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
												<td>".$row['CLIENTE']."</td>
												<td>".$row['CHAPA']."</td>
												<td>".$row['NOME']."</td>
												<td>".$row['SECAO']."</td>
												<td>".$row['DESCRICAO_OS']."</td>
												<td>".$result."</td>
												<td>".$resultF."</td>
												<td><input type='checkbox' class='checkbox checkbox-success selected'  name='status[]' value='A-".$row['ID']."'' id='once-select' /></td>
												<td><input type='checkbox' class='checkbox checkbox-danger deselected chb' name='status[]' value='R-".$row['ID']."'' id='once-deselect' /></td>
											</tr>";
										}?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="form-row" align="right">
						<div class="col-sm-12 form-group has-warning has-feedback" >							
							<input type="submit" id="checar" name= "acao"  class="btn btn-primary"  tabindex="5" onclick= "verificaCheck()" value="Executar Ação" <?php echo $permission ?> />
							<input type="button" name= "novoapontamento" class="btn btn-primary" onclick="novoApontamento()"  tabindex="5" value="Novo Apontamento" />
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
	
function verificaCheck(){

var elems = $('#tabDashBoard tbody tr').find('input:checkbox:checked');
var values = [].map.call(elems, function(obj) {

  return obj.value;
  
	});

}
	
</script>	
	
<script> 
function novoApontamento(){
  gerencia.action = 'confirmaApontamento.php'; 
  gerencia.submit();
}
</script>

<script> 
function filtraTabela(){
  gerencia.action = 'gerencia.php'; 
  gerencia.submit();
}
</script>

  
  <script type="text/javascript">
  
  jQuery(function($) {
        $('body').on('click', '#selectall', function() {
              $('.checkbox-success').prop('checked', this.checked );
			 			 
        });
		  $('body').on('click', '#selectallrem', function() {
              $('.deselected').prop('checked', this.checked);
			  
			  
        });
 
        $('body').on('click', '.checkbox-success', function() {
            if($(".checkbox-success").length == $(".checkbox-success:checked").length) {
                $("#selectall").prop("checked", "checked");
				
            } else {
                $("#selectall").removeAttr("checked");
            }
 
        });
		
		 $('body').on('click', '.deselected', function() {
			 if($(".deselected").length == $(".deselected:checked").length) {
                $("#selectallrem").prop("checked", "checked");
            } else {
                $("#selectallrem").removeAttr("checked");
            }
 
        });
  });
</script>
</body>
</html>