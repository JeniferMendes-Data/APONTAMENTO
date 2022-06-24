<?php
if (session_status() == 1){
    session_start();
}
?>
<!doctype html>
<html lang="pt-br">
	<head>
		<?php
		include 'functions/include.php';
		include 'functions/global_functions.php';

		echo include_head("APONTAMENTO | LOGIN");
		?>
	</head>
	<body>
		<div class="container" style="margin-top: 100px !important;">
            <div class="form-group">
                <form method="post" action="<?php echo "functions/post.php?pag=login";?>" id="login">
                	<div class="shadow-lg col-md-6 offset-md-3">
                        <div class="pb-3 text-center">
                        	<img src=<?php echo "http://".$_SERVER["HTTP_HOST"]."/_utilitaries/img/hora_200.png";?> id="icon" alt="Apontamento" class="img-responsive h-100" style="width:200px;">
                        </div><div class="col-md-12 pb-3 text-center">
                            <h5>Apontamento de Horas</h5>
                        </div>
                        <div class="row justify-content-center pb-3">
                            <div class="col-md-10">
                            	<input type="text" id="idlogin" class="form-control" name="login" placeholder="Usuário" maxlength="50" required>
                            </div>
                        </div>
                        <div class="row justify-content-center pb-3">
                            <div class="col-md-10">
                             	<input type="password" pattern="[0-9]*" id="senha" class="form-control" name="senha" placeholder="Senha" maxlength="16" required>
                             </div>
                        </div>
                        <div class="col-md-12 text-center pb-3">
                            <input type="submit" class="btn btn-primary" value="Acessar" name="acessar" id="acessar">
                        </div>
                        <div id="recuperaSenha" class="text-center pb-3">
                          <a class="" href="#">Recuperar Senha</a>
                        </div>
                	</div>
					<?php
					if (session_status() == 1){
					    session_start();
					}

					//insere modal de retorno
						if(isset($_SESSION['msg'])){
						     if ($_SESSION['msg'] == 'sucessoLogin') {
						         unset($_SESSION['msg']);
						         $_SESSION['origem'] = 'login';
						         header("Location: /views/home.php");
						         die();
						     }else if($_SESSION['msg'] == 'erroPagina'){
						         echo include_modal("idRetornoLogin","Efetue login novamente", "Sessão expirada!", "sucesso");
						         global_geraLog( "Tentativa de login mal sucedida", 'error' );
						         session_unset();
						         session_destroy();
						     }else{
								 session_unset();
								 session_destroy();
						         //função de include.php
						         echo include_modal("idRetornoLogin","Usuário Não encontrado", "Usuário e/ou senha inválidos!", "erro");
						         global_geraLog( "Tentativa de login mal sucedida - Session MSG não informada - LOGIN: ".$_SESSION['usuario'], 'error' );
						     }
						}else{
							global_geraLog( "Tentativa de login mal sucedida - Session MSG inexistente", 'error' );
							session_unset();
							session_destroy();
						}
					?>
					<script language= "JavaScript">$(document).ready(function(){ $("#idRetornoLogin").modal("show"); });</script>
                </form>
            </div>
        </div>
	</body>
</html>