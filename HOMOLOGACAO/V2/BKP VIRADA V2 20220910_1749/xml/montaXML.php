<?php 
	
  
//chama essa função atraves do action da pagina de gerencia. 
	
   
	$xmlRoot = simplexml_load_file("apontamento.xml");
	
	
if(!$xmlRoot){
	
	$createXML= $xmlRoot->createElement("MovMovimento");
	$xmlRoot->appendChild($createXML);
	
		/*foreach($xmlRoot->children() as $tmov){
		}Verificar condicionamento mais tarde*/
		
}else{

	$createXML = $xmlRoot->firstChild;	
	print_r($createXML);
	die();
	
}

if(isset($_POST['acao'])){
	if(){
	
	$tmov = $xmlRoot->createElement("TMOV");
	$createXML->appendChild($tmov);	

	
}



    
?>



<?php
/* verificar coigo mais tarde
	
$xml = new SimpleXMLElement('<xml/>');

for ($i = 1; $i<=quantidade que deseja percorrer ; ++$i) {
    $track = $xml->addChild('track');
    $track->addChild('path', "song$i.mp3");
    $track->addChild('title', "Track $i - Track Title");
}

Header('Content-type: text/xml');
print($xml->asXML());
*/
?>