
<?php session_start(); 

require "../sql/conectaDss.php";
	
$login = $_SESSION['chapa'];
$h_ini = $_SESSION['horainicio'];
$h_fim = $_SESSION['horafim'];
$os = $_SESSION['n_nos'];
$retrabalho = $_SESSION['retrabalho'];
$servCampo = $_SESSION['servCampo'];
$causaRetrabalho = $_SESSION['causaRetrabalho'];												

if($servCampo == 'S'){
	$sCampo = $servCampo;
}else {
	$sCampo = 'N';
}

if($retrabalho =='N'){
	$retrab = 'NA';
}else {
	$retrab = $causaRetrabalho;
}

$sql = "INSERT INTO LOG_APONTAMENTO (N_OS, ID_USUARIO, VALIDA, H_INICIO, H_FIM, H_LANCAMENTO,ORIGEM, RETRABALHO, SERV_CAMPO ) VALUES ('$os', '$login', 'P', CONVERT(varchar(11),getdate(),20) + '$h_ini' ,CONVERT(varchar(11),getdate(),20) + '$h_fim' , GETDATE(), 'APP DO TI', '$retrab', '$sCampo' )";


$stmt = sqlsrv_query( $conn, $sql);
$result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

	$_SESSION['menssage']= 'sucesso';	
	header("Location: apontamentoColab.php?menssage=sucesso");
?>



