<?php session_start();

require "../sql/conectaDss.php";


if(isset($_POST['acessar'])){


$login = $_POST['usuario'];
$senha = $_POST['senha'];
$_SESSION['chapa'] = $_POST['usuario'];

$sql = "select * from VIEW_DADOS_USUARIOS where LOGIN = '{$login}' AND VPCOMPL_PWDDSS = '{$senha}'";

$resultado_usuario = sqlsrv_query($conn, $sql);

$resultado = sqlsrv_fetch_array($resultado_usuario, SQLSRV_FETCH_ASSOC);




	if(isset($resultado)){

		$_SESSION['usuarioServCampo'] = $resultado['GRUPO_FLUIG'];

        $_SESSION['senha'] = $resultado['senha'];
        $_SESSION['usuarioNiveisAcessoId'] = $resultado['VPCOMPL_SUP'];

        if($_SESSION['usuarioNiveisAcessoId'] == "SUB"){
            header("Location: decisaoColab.php");
        }elseif($_SESSION['usuarioNiveisAcessoId'] <> "SUB"){
            header("Location: decisao.php");
        }else{


        }
	}else{

	$_SESSION['msg']='sucesso';

	header("Location: ../index.php?msg=sucesso");


    }

}
?>