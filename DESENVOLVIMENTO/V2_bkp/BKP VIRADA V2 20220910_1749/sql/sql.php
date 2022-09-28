<?php
	define('DB_HOST'		,"192.168.0.10");
	define('DB_USER'		,"sa");
	define('DB_PASSWORD'	,"Arh#100121");
	define('DB_NAME'		,"CORPORERM");
	define('DB_DRIVER'		,"sqlsrv");
	
$server = "192.168.0.10";
$connectionInfo = array( "Database"=>"CORPORERM", "UID"=>"sa", "PWD"=>"Arh#100121" );
$conn = sqlsrv_connect( $server, $connectionInfo );

class conect
{
	private static $connection;
	private function __construct(){}
	public static function getConnection() {
	
		$pdoConfig  = DB_DRIVER . ":". "Server=" . DB_HOST . ";";
		$pdoConfig .= "Database=".DB_NAME.";";
		 
		try {
			if(!isset($connection)){
				$connection =  new PDO($pdoConfig, DB_USER, DB_PASSWORD);
				$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			return $connection;
		 } catch (PDOException $e) {
			$mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
			$mensagem .= "\nErro: " . $e->getMessage();
			throw new Exception($mensagem);
		 }
	 }
}

function f_sql_query ($param_sql){

	/*
	PARA USO DA FUNÇÃO DECLARAR UMA SENTENÇA SQL NA VARIAVEL E CHAMAR A FUNÇÃO f_sql_query COM A VARIAVÉL COMO PARAMETRO;
	
	$myquery1 = "
		SELECT TOP 100
			*
			FROM GCONSIST
			ORDER BY 2
	";
	f_sql_query($myquery1);
	
	*/
	
	echo "<pre>";
	try{
		
		$conect  = conect::getConnection();
		$query = $conect->query($param_sql);
		$consult = $query->fetchAll();
	}
	catch(Exception $error){
		echo $error->getMessage();
		exit;
	}
	
	foreach($consult as $consult_child) {
		$max_array = count($consult_child)/2;
		for ($i = 0; $i <= $max_array; $i++) {
			echo "[" . $consult_child[$i] . "]";
		}
		echo "<br>";
		
	}
	echo "</pre>";
}



?>

