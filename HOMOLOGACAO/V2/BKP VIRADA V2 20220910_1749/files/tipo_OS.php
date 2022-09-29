<?php
	include_once '../DSS2/_apontamento/conexao.php';
	
		
	
	$result_list_os = "	select 	CODCLIENTE,DESCRICAO	from GCONSIST		WHERE CODTABELA= '000'		AND CODCOLIGADA = '1'		AND CODCLIENTE <= '000031' 		AND APLICACAO ='T'";
	sqlsrv_query($conn, $result_list_os);	
			
	$result = sqlsrv_query($conn, $result_list_os);
	
	
	
	
	//verifica se econtrou resultado na tabela
	if(($result) AND ($result->num_rows != 0)){
		while ($row_OS = sqlsrv_fetch_assoc($result)){			
			echo $row_OS['DESCRICAO']
			}	
		}else{
			echo "Nenhuma OS encontrada, entre em contato com o T.I"
		}
	
		
	/*$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt = sqlsrv_query( $conn, $sql , $params, $options );

	$row_count = sqlsrv_num_rows( $stmt );
   
	if ($row_count == 0)
		echo "Usuário não cadastrado no sistema ou senha inválida.";
	else

		header("Location:apontamento.php"); 

	*/

?>
