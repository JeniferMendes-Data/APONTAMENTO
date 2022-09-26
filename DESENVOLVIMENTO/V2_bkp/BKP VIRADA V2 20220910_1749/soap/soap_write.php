<?php

/*
require "http://dss/DSS/soap/xml.php";
f_soap_write('MovMovimentoTBCData',$xml_soap);
*/


function f_soap_write($DataServerName,$XML_ENTRADA){

//-----------------------------------------------------------------------------
	$user = "fluig";
	$pass = "Arh#100121";
	$soapauth = array("login"=> $user,"password"=> $pass, "trace" => 1);
	$wsdl_DataServer = "http://192.168.0.4:8051/wsDataServer/MEX?wsdl";
	$soap_client = new SoapClient($wsdl_DataServer,$soapauth);

	$soap_function = "SaveRecord";
	//$DataServerName = "MovMovimentoTBCData";
	//$XML_ENTRADA = $xml_soap;
	$Contexto = "CODCOLIGADA=1;CODUSUARIO=fluig;CODSISTEMA=G";
	$arguments = ["SaveRecord" => ["DataServerName" => $DataServerName,"XML" => $XML_ENTRADA,"Contexto" => $Contexto]];

//-----------------------------------------------------------------------------

	try{

		$result = $soap_client->__soapCall($soap_function, $arguments);

		//EXIBE O RESULTADO BRUTO DO SOAP
		var_dump($result);
		
		//ATRIBUI A VARIAVEL result_xml O XML DE RESPOSTA DO SOAP EM FORMATAÇÃO XML
		$result_xml =$soap_client->__getLastResponse();		
		
		$_SESSION['result_xml'] = $result_xml;
		
	
	} catch (SoapFault $error){
//-----------------------------------------------------------------------------
		//O erro do Web Service ou mensagem de falha para ser tratado.
		
		echo "<pre><hr><h1 align='center'> PÁGINA DE ERRO SOAP.PHP </h1><hr>";
		
		echo "<hr><h2> ERROR </h2><hr>";
		var_dump($error);

		echo "<hr><h2> soap_client </h2><hr>";
		var_dump($soap_client);
		
		echo "<hr><h2> result </h2><hr>";
		var_dump($result);
		
		echo "<hr><h2> arguments </h2><hr></pre>";
		var_dump($arguments);
	}
	
	
}
?>

