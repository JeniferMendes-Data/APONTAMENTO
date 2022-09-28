<?php session_start();

require "../sql/conectaDss.php";
	
$nos = $_GET['numero_os']; 
	if(strlen($nos) == 5 ){
		$numOs = '0000' . $nos;
		}elseif(strlen($nos) == 6){
		$numOs= '000'. $nos;
		}elseif($nos <= '000033'){
		$numOs= '000'. $nos;
		}
		
	

$sql = "select * from VIEW_MODAL_OS where TMOV_NUMEROMOV = '{$numOs}'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query($conn, $sql, $params, $options);
$row_count = sqlsrv_num_rows($stmt);
$dados = array();
	
	if($row_count <= 0){
		$dados[] = array(
			'COD_RETORNO' => false
		);
	} else {
		while($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)){
		$dados[] = array(
			'TMOV_NUMEROMOV' => $row['TMOV_NUMEROMOV'],
			'TMOVCOMPL_DESCRICAOCOMP' => utf8_encode($row['TMOVCOMPL_DESCRICAOCOMP']),
			'COD_RETORNO'=> true
			);
		}
	}
	
	
	

	
	echo json_encode($dados);
	
	


?>