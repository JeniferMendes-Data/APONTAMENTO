<?php
include $_SERVER["DOCUMENT_ROOT"].'/functions/query.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/functions/global_functions.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/functions/post.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/_utilitaries/config.php';

if (isset($_POST["recuperaDadosOS"])) { //seleciona os dados da OS no RM na tela views/apontar.php -> inpNumOS
    echo json_postRecuperaDadosOS();

} elseif (isset($_POST["recuperaDadosParteAtiv"])){ //seleciona os dados da Parte e da Atividade no RM na tela views/apontar.php -> selParte e selAtiv
    echo json_postRecuperaDadosParteAtiv();

}elseif (isset($_POST["recuperaSecaoNomeSup"])){ //seleciona os dados de Seção, Descrição, Nome, Chapa e Login dos colaboradores da seção para as telas views/apontar.php -> selSecao e views/gerenciar.php -> selNome
    echo json_postRecuperaSecaoNomeSup();
}elseif (isset($_POST["recuperaApontamento"])){ //seleciona os dados de apontamentos realizados na data
    echo json_postRecuperaApontamento();
}elseif (isset($_POST["aprovaApontamento"])){ //altera o status do apontamento
    echo json_postAprovaApontamento();
}elseif (isset($_POST["checaIntervalo"])){ //recupera apontamento para o período
    echo json_postChecaIntervalo();
}elseif (isset($_POST["listaOSGenerica"])){ //recupera apontamento para o período
    echo json_postListaOSGenerica();
}

//-------------------- INICIO FUNÇÕES --------------------

function json_postRecuperaDadosOS() {
    $jsonRetorno = querySelect_dadoOS($_POST["recuperaDadosOS"]["numOS"]);
    echo json_encode($jsonRetorno);
}

function json_postRecuperaDadosParteAtiv() {
    $idSecao = "";
    $result = querySelect_idSecao($_POST["recuperaDadosParteAtiv"]["codSecao"]);
    $idSecao .= $result[0]["ID"];
    $jsonRetorno = querySelect_lista($idSecao);

    if ((is_countable($jsonRetorno)?count($jsonRetorno):0) == 0) {
        $jsonRetorno[0]['PARTE'] = 0;
        $jsonRetorno[0]['DESCRICAO_PARTE'] = 'Nao foi possivel carregar os itens...';
        $jsonRetorno[0]['ATIV'] = 0;
        $jsonRetorno[0]['DESCRICAO_ATIV'] = 'Nao foi possivel carregar os itens...';
    }

    echo json_encode($jsonRetorno);
}

function json_postRecuperaSecaoNomeSup() {
    $json = $_POST["recuperaSecaoNomeSup"];

    echo json_encode(querySelect_secaoColaborador($json["usuarioLogado"]));
}

function json_postRecuperaApontamento(){
    $json = $_POST["recuperaApontamento"];

    echo json_encode(querySelect_buscaApontamento($json["stringUsuario"], $json["data"]));
}

function json_postAprovaApontamento(){
    $json = $_POST["aprovaApontamento"];

    echo json_encode(post_getApontar($json["id"], $json["acao"], $json["campos"]));
}

function json_postChecaIntervalo(){
    $json = $_POST["checaIntervalo"];

    $dataIni =  date_format(date_create_from_format('d/m/Y H:i:s', $json["hraIni"]), 'Y-m-d H:i:s');
    $dataFim =  date_format(date_create_from_format('d/m/Y H:i:s', $json["hraFim"]), 'Y-m-d H:i:s');

    echo json_encode(querySelect_checaIntervalo($dataIni, $dataFim, $json["login"], $json["id"]));
}

function json_postListaOSGenerica(){
    $config = new Config();

    echo json_encode($config->OSGenerica);
}
?>