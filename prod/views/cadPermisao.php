<?php
if (session_status() == 1){
    session_start();
}
include $_SERVER["DOCUMENT_ROOT"].'/functions/include.php';
?>
<!doctype html>
<html lang="pt-br">
	<head>
    <?php echo include_head('APONTAMENTO | CADASTRAR');?>
	</head>
	<body>
    <?php
           //verifica se a sessão está ativa
           if (!isset($_SESSION["nomeUsuario"])) {
            header("Location: ../index.php");
            die();
        } 	
       
    	?>
        <body>