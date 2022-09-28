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
		include_once 'functions/global_functions.php';

		echo include_head("APONTAMENTO | LOGIN");
		?>
	</head>
	<body>
		<h1 class="text-center text-danger">AMBIENTE DE TESTES</h1>
		<div class="container position-absolute top-50 start-50 translate-middle" style="">
            <div class="form-group">
                <form method="post" action="<?php echo "functions/post.php?pag=login";?>" id="login">
                	<div class="shadow-lg col-md-6 offset-md-3 bg-light">
                        <div class="pb-3 text-center">
                        	<img src=<?php echo "http://".$_SERVER["HTTP_HOST"]."/_utilitaries/img/hora_200.png";?> id="icon" alt="Apontamento" class="img-responsive h-100" style="width:200px;">
                        </div><div class="col-md-12 pb-3 text-center">
                            <h5>AMBIENTE DE TESTES</h5>
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
                            <input type="submit" class="btn btn-primary" name="acessar" value="TESTAR" id="acessar">
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
						         if (headers_sent()) {
									die("O redirecionamento falhou.");
								}
								else{
									exit(header("Location:/views/home.php"));
								}		
						     }else if($_SESSION['msg'] == 'erroPagina'){
						        //chama modal bootbox
								 echo "<script>$(document).ready(function(){bootbox.alert({buttons: {ok: {label: 'Fechar',className: 'bg text-light'},},centerVertical: true,title: 'Efetue login novamente',message: 'Sessão expirada!'});})</script>"; 														         
								 global_geraLog( "Tentativa de login mal sucedida", 'error' );
						         session_unset();
						         session_destroy();
						     }else{
								$usuario = $_SESSION['usuario'];
								 session_unset();
								 session_destroy();
								 //chama modal bootbox
								 echo "<script>$(document).ready(function(){bootbox.alert({buttons: {ok: {label: 'Fechar',className: 'bg text-light'},},centerVertical: true,title: 'Usuário Não encontrado',message: 'Usuário e/ou senha inválidos!'});})</script>";
						         global_geraLog( "Tentativa de login mal sucedida - Session MSG não informada - LOGIN: ".$usuario, 'error' );
						     }
						}else{
							global_geraLog("Tentativa de login mal sucedida - Session MSG inexistente", 'error');
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