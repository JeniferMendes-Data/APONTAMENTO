<?php
if (session_status() == 1){
    session_start();
}
//*** Funções com finalidade de realizar include nas views. PADRÃO: nome da função = include_ '+ objetivo'
include_once($_SERVER["DOCUMENT_ROOT"].'/_utilitaries/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/functions/query.php');

//Função para incluir a tag 'head' em todas as páginas
function include_head($titulo){
    $retorno = '<!-- '.$titulo.'-->
                <title>'.$titulo.'</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" href="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/css/bootstrap-select.css">
                <link rel="stylesheet" href="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/css/bootstrap-datepicker.css">
                <link rel="stylesheet" href="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/css/bootstrap.min.css">                
                <link rel="stylesheet" href="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/css/custom_style.css">
                <link rel="stylesheet" href="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/css/jquery-ui.min.css">
                <link rel="stylesheet" href="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/css/fullCalendar-main.min.css">
                <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/jquery-3.6.0.min.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/jquery-ui.min.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/jquery.inputmask.min.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/bootstrap.bundle.min.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/bootstrap-select.min.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/bootstrap-datepicker.min.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/bootstrap-datepicker.pt-BR.min.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/bootbox.min.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/bootbox.locales.min.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/custom_global.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/custom_apontar.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/custom_gerenciar.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/fullCalendar-main.min.js"></script>
                <script src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/js/fullCalendar-pt-br.js"></script>';
    return $retorno;
}

//Função para criar o menu do cabeçalho das telas. $paginaAtual (array) = chave: nome da tela a ser exibida, valor: endereço da página a ser exibida - $titulo = Título da página que está chamando a função
function include_menu($paginaAtual, $titulo){
    //Informar como chave nome da tela a ser exibida no título e endereço da página como valor
    $paginas = array("Início" => "home.php", "Apontar" => "apontar.php", "Gerenciar" => "gerenciar.php");
    $retorno = '<nav class="navbar navbar-expand-lg navbar-dark bg">
                    <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="http://'.$_SERVER["HTTP_HOST"].'/_utilitaries/img/logo_data.jpg" width="100" alt="">
                    </a>
                    <h5 class="text-light font-weight-bold">Apontamento de Horas</h5>
                    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#conteudoNavbarSuportado" aria-controls="conteudoNavbarSuportado" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="navbar-collapse mx-lg-5 collapse" id="conteudoNavbarSuportado" style="">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">';

    foreach($paginas as $key => $value){        
        $active = "active";
        $classe = "text-danger font-weight-bold"; 

        if ($key == "Cadastrar") {
            if ($key !== $paginaAtual) {                    
                $active = "";
                $classe = "";
            }    
            $retorno .= '<div class="btn-group btn"><li class="nav-item dropdown text-light navbar-text "'.$active.' ><a class="btn-group '.$classe.'" href="cadastrar.php" style="text-decoration: none;">Cadastrar |<a class="dropdown-toggle btn-group" type="button" id="dpdCadastrar" data-bs-toggle="dropdown" aria-expanded="false"></a></a><ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dpdCadastrar"><li><a class="dropdown-item" href="osGenerica.php">Os Generica</a></li><li><a class="dropdown-item" href="partePeca.php">Parte e Peça</a></li><li><a class="dropdown-item" href="causaRetrabalho.php">Causa do Retrabalho</a></li><li><a class="dropdown-item" href="cadPermisao.php">Permissões</a></li><li><a class="dropdown-item" href="cadAbrirPeriodo.php">Abrir Periodo</a></li></ul></li></div>';
        }elseif ($key == $paginaAtual){
            $retorno .= '<li class="navbar-text nav-item '.$active.'"><a class="nav-link '.$classe.'" href="'.$value.'">'.$key.' |</a></li>';
        }else{
            $retorno .= '<li class="navbar-text nav-item"><a class="nav-link text-light" href="'.$value.'">'.$key.' |</a></li>';
        }
    }

    $retorno .='</ul>
                <div class="d-flex">
                    <label class="me-2 text-light">Olá, '.$_SESSION["nomeUsuario"].'</label>
                    <a class="text font-weight-bold btn btn-danger" id="logout" type="button" href="http://'.$_SERVER["HTTP_HOST"].'/index.php?msg=logout">Sair</a>
                </div>
            </div>
        </div>
    </nav>
    <h3 class="text-center my-3" id="tituloPagina">'.$titulo.'</h3>';

    return $retorno;
}

//Função para retornar a lista de OS's genéricas views -> apontar.php
function include_itemOSGenerica($origem = "") {
    $retorno = "";
    $numOSGenerica = new Config();
    $result = querySelect_dadoOS($numOSGenerica->OSGenerica);
    sort($result);
    foreach ($result as $row){
        $numOS = substr($row['TMOV_NUMEROMOV'], 3);
        if ($origem == ""){
           $onclick = "js_apontarSelecionaItem(\'".$numOS."\',\'".$row['TMOVCOMPL_DESCRICAOCOMP']."\',\'".$row['GFILIAL_CIDADE']."\',\'".$row['GCCUSTO_NOME']."\')";
        }else{
            $onclick = "js_apontarSelecionaItem(\'".$numOS."\',\'".$row['TMOVCOMPL_DESCRICAOCOMP']."\',\'".$row['GFILIAL_CIDADE']."\',\'".$row['GCCUSTO_NOME']."\',\'ger\')";
        }
        $retorno .= "<a class=\"dropdown-item\" onclick=\"".$onclick."\">".$numOS."-".$row['TMOVCOMPL_DESCRICAOCOMP']."</a>";
    }
    return $retorno;
}

//Função para retornar a lista de Horas e minutos views -> apontar.php
function include_itemHraEMin($tipo) {
    $lista = new Config();
    $listaArray = explode(',',($tipo == "hora")?$lista->listHrs:$lista->listMin);
    foreach ($listaArray as $row){
        $retorno .= "<option value='$row'>$row</option>";
    }
    return $retorno;
}

//Função para retornar a lista de causas do Retrabalho views -> apontar.php
function include_causaRetrabalho() {
    $result = querySelect_causaRetrabalho();
    $retorno = "";
    foreach ($result as $row){
        $retorno .= '<option value="'.$row['CODINTERNO'].'">'.$row['DESCRICAO'].'</option>';
    }
    return $retorno;
}

?>