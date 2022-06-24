<?php
if (session_status() == 1){
    session_start();
}
include_once $_SERVER["DOCUMENT_ROOT"].'/functions/include.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions/global_functions.php';
?>
<!doctype html>
<html lang="pt-br">
	<head>
		<?php echo include_head('APONTAMENTO | INÍCIO') ;?>
	</head>
	<body>
    	<?php
        	//monta o cabeçalho
        	echo include_menu("Início","Configuração");
    		//verifica se a sessão está ativa
        	if (!isset($_SESSION["nomeUsuario"])) {
        	    $_SESSION["msg"] = "erroPagina";
        	    header("Location: ../index.php");
        	    die();
        	}else if(isset($_SESSION["origem"]) && $_SESSION["origem"] == "login"){
        	    unset($_SESSION["origem"]);
        	    //mensagem de boas-vindas
        	    echo include_modal("idRetornoLogin","Apontamento de Horas", "Bem-vindo ".$_SESSION['nomeUsuario']. "!", "sucesso");
        	}
    		//verifica permissões do usuário
        	if (isset($_SESSION["CAD_PER"]) && $_SESSION["CAD_PER"] == 1) {
    		    $readyPermissoes .= "js_exibeCampos('CAD_PER', '', false);"; //prepara string para inserir no onReady da página para cada permissão
    		}
    	?>
    	<script language= "JavaScript">$(document).ready(function(){ $("#idRetornoLogin").modal("show"); });</script>
    	<div class="container mt-5">
            <div class="row">
            	<div class="col-md-6 text-center">
                	<a href="apontar.php" class="bg px-5 text-light btn">Apontar</a>
                </div>
                <div class="col-md-6 text-center">
                	<a href="gerenciar.php" class="btn bg px-5 text-light">Gerenciar</a>
                </div>
             </div>
        </div>
    	<form action="../functions/post.php?pag=home" method="post" id="enviaConf" onsubmit="js_onSubmit()">
        	<div class="container">
                <div class="mt-5 CAD_PER" style="display:none;">
                    <div class="row">
                    	<div class="form-check">
                            <input id="inpLiberarPeriodo" type="checkbox" class="form-check-input" onclick="js_exibeCampos('CAD_PER_CHILD', document.getElementById('inpLiberarPeriodo').checked==true?'':'none', false)" name="inpLiberarPeriodo" value="checked"/>
                            <label for="inpLiberarPeriodo" class="form-check-label h5 font-weight-bold">Liberar Período</label>
                        </div>
                    </div>
                    <div class="row CAD_PER_CHILD" style="display:none;">
                    	<div class="col-md-6 shadow-sm mt-1" id="divPerLanDis">
                    		<label for="divPerLanDis" class="label">Período em que o lançamento ficará disponível: </label>
                    		<div class="row">
                        		<div class="col-md-6">
                        			<input id="inpPerLanIni" type="datetime-local" class="form-control REQUIRED" name="inpPerLanIni" placeholder="Início"/>
                        		</div>
                        		<div class="col-md-6">
                        			<input id="inpPerLanFim" type="datetime-local" class="form-control REQUIRED" name="inpPerLanFim" placeholder="Fim"/>
                        		</div>
                        	</div>
                        </div>
                        <div class="col-md-6 shadow-sm mt-1" id="divPerLanRet">
                        	<label for="divPerLanRet" class="label">Período p/ lançamento retroativo:</label>
                        	<div class="row">
                            	<div class="col-md-6">
                                	<input id="inpPerRetIni" type="datetime-local" class="form-control REQUIRED" name="inpPerRetIni" placeholder="Início"/>
                                </div>
                                <div class="col-md-6">
                                	<input id="inpPerRetFim" type="datetime-local" class="form-control REQUIRED" name="inpPerRetFim" placeholder="Fim"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 shadow-sm mt-1">
                        	<label for="inpObs">Observação</label>
                        	<div class="row">
                        		<input type="text" class="form-control" id="inpObs">
                        	</div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end mt-1">
                	<buttom id="btnSalvar" type="submit" class="btn btn-primary col-md-1" value="Salvar" name="Salvar" style="display:none;">Salvar</buttom>
                </div>
            </div>
        </form>
		<script>
		//função para chamada de modal em tempo de execução
		function js_onSubmit(){
			bootbox.confirm({message:"Confirma o envio dos dados?", function(result){return result;}});
		}

		//função para exibição de modal e ajuste de título conforme permissão em tempo de execução
			$(document).ready(function(){
				$("#idRetornoLogin").modal("show");
				$("input").change(function(){
                    document.getElementById("btnSalvar").style.display = "";
                });
				<?php
				if (empty($readyPermissoes)) {
				    echo "document.getElementById('tituloPagina').innerHTML = 'Início'; document.getElementById('btnSalvar').style.display = 'none'";

				}else{
				    echo ($readyPermissoes);
				}
				 ?>
			});

		</script>
	</body>
</html>