<?php session_start();

require "../sql/conectaDss.php";
	
$chapa = $_GET['chapa']; 
	



$sql2 = "select * from VIEW_DADOS_USUARIOS where LOGIN = '{$chapa}'";
$stmt2 = sqlsrv_query($conn, $sql2);




$dados = array();

	while($row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)){
	
		$dados[] = array(
		'LOGIN' => $row['LOGIN'],
		'PPESSOA_NOME' => utf8_encode($row['PPESSOA_NOME']),
		'PFUNC_CODSECAO'=> $row['PFUNC_CODSECAO']
		);
		
	}
	
	echo json_encode($dados);


?>