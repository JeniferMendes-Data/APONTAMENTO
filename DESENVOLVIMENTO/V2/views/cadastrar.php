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
            
            echo include_menu("Cadastrar","Cadastrar");
       
    	?>
        <body>
            <div class="list-group text-center my-3 container-md col-md-6 configdiv">
                <a href="cadAbrirPeriodo.php" class="list-group-item list-group-item-action alterahover">Abrir Periodo</a>
                <a href="cadCausaRetrabalho.php" class="list-group-item list-group-item-action alterahover">Causa do Retrabalho</a>
                <a href="cadPartePeca.php" class="list-group-item list-group-item-action alterahover">Parte e Peça</a>
                <a href="cadPermissao.php" class="list-group-item list-group-item-action alterahover">Permissão</a>
                <a href="cadOsGenerica.php" class="list-group-item list-group-item-action alterahover">OS Genericas</a>                
            </div>
        </body>
    </html>