
<!---------------------------------------------------------------------------->
<!--PHP REQUIRE-->
	<?php //require "../DSS2/sql/sql.php";?>
	<?php require "../xml/xml.php";?>
	<?php //require "../DSS2/_apontamento/soap_read.php";?>
	<?php require "soap_write.php";?>

<!---------------------------------------------------------------------------->
<!--CHAMA FUNÇÃO-->
	<?php
		echo "<pre><hr><h1 align='center'> TESTE 004 </h1><hr>";
		f_soap_write('MovMovimentoTBCData',$xml_soap);
	?>
<!---------------------------------------------------------------------------->
