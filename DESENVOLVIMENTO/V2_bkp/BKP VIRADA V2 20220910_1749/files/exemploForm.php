<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de apontamento de OS''s</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
</head>
<body>
    <script language=''JavaScript''>
        function SomenteNumero(e){
            var tecla=(window.event)?event.keyCode:e.which;   
            if((tecla>47 && tecla<58)) return true;
            else{
                if (tecla==8 || tecla==0) return true;
                else  return false;
            }
        }
    </script>
    <?php
    if(!empty($_SESSION[''cadastro''])){ 
        if($_SESSION[''permite_cadastrar''] == 0){
            ?>
            <script language="JavaScript"> 
                window.location="inicial.php"; 
                window.alert("Acesso Negado! Sem permissão!");
            </script> 
            <?php
        }   
    }else{
        $_SESSION[''msg''] = "Área restrita";
        ?> 
        <script language="JavaScript"> 
            window.location="index.php"; 
            window.alert("Area Restrita");
        </script> 
 
        <noscript> 
            Se não for direcionado automaticamente, clique <a href="../index.php">aqui</a>. 
        </noscript>
        </script>
        <script type="text/javascript">
            function valida(){
                window.alert("Olá!")
            }
        </script>
        <?php
    }
$servidor = "localhost";
$login = "maicon.friedel";
$senha = "IGszKJk46GMc3BCE";
$dbname = "mercotoys";
$ref = 123;
    $conn = mysqli_connect($servidor, $login, $senha, $dbname);
    $referencia1 = "SELECT descricao from referencias where referencia = ''$ref''";
    $referencia = mysqli_query($conn, $referencia1);
    $linhas = mysqli_num_rows($referencia);
    while ($linhas = mysqli_fetch_array($referencia)){
        $referencia3 = $linhas[''descricao''];
    }
    ?>
    <script type="text/javascript" src="../DSS2/_apontamento/date_hour_mask.js"></script>
    <p align="right"><a href="../DSS2/_apontamento/inicial.php">Voltar a Página Inicial</a> </p>
    <div class="container">
        <br>
        <h1>Cadastro de OP</h1>
        <br>
        <form class="form-horizontal" method="POST" action="../DSS2/_apontamento/cadastra.php" name="form1">
            <div class="row">
                <div class="col-md-2">
                    <label>OP: </label>
                    <input type="text" name="op" class="form-control" placeholder="OP" onkeypress="return SomenteNumero(this)" required>
 
                </div>
 
                <div class="col-md-2">
                    <label>Lote: </label>
                    <input type="text" name="lote" class="form-control" placeholder="Lote" onkeypress="return SomenteNumero(this)">
                </div>
 
                <div class="col-md-2">
                    <label>Referência: </label>
                    <input type="text" name="referencia" class="form-control" placeholder="Referência" id="ref" onkeypress="return SomenteNumero(this)" required onblur="valida(this)">
 
                </div>
                <div class="col-md-3">
                    <label>Descrição: </label>
                    <input type="text" name="descricao" class="form-control" placeholder="Descrição" id ="descricao" required>
                </div>
                <div class="col-md-3">
                    <label for="data">Data: </label>
                    <input type="date" name="data" class="form-control" required/>
                </div>
            </div>    
            <div class="row">
                <div class="col-md-4">
                    <label for="operadores">Operadores: </label>
                    <textarea name="operadores" class="form-control" rows="4" id="operadores" placeholder="Informe os operadores, por ordem de Posto" required></textarea>
                </div>
                <div class="col-md-2">
                    <label>Controle Qualidade:</label>  
                    <input type="text" name="qualidade_inicial" class="form-control" required placeholder="Inicial" onkeypress="return SomenteNumero(this)" /><br>
                    <input type="text" name="qualidade_final" class="form-control" required placeholder="Final" onkeypress="return SomenteNumero(this)" />
 
                </div>
                <div class="col-md-2">
                    <label>Hora Início e Fim: </label>
                    <input type="text" name="inicio" class="form-control" placeholder="Hora Início" onkeypress="valida_horas(this)"  maxlength="5" required>
                    <br>
                    <input type="text" name="fim" class="form-control" placeholder="Hora Fim" onkeypress="valida_horas(this)" maxlength="5" required>
                </div>
                <div class="col-md-2">
                    <label>Parada: </label>
                    <input type="text" name="parada" class="form-control" placeholder="Minutos parados" onkeypress="return SomenteNumero(this)">
                    <br>
                    <input type="text" name="motivo" class="form-control" placeholder="Motivo Parada" >
                    <p> <input type="checkbox" name="lanche" value="lanche"> <label>Parada do Lanche</label></p>  
 
                </div>
                <div class="col-md-2">
 
                    <br>
                    <label>Quantidade de Peças: </label>
                    <input type="text" name="qtd_pecas" class="form-control" placeholder="Qtd Peças" required onkeypress="return SomenteNumero(this)">
                </div>
            </div>
            <br>
            <br>
            <p align="center"><input type="submit" name="submit" value="Registrar" class="btn btn-success"/></p>
        </form>
        <p id="desc"></p>
    </div>
 
</body>
</html>