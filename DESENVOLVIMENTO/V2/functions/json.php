<?php
include $_SERVER["DOCUMENT_ROOT"].'/functions/query.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/functions/global_functions.php';

if (isset($_POST["recuperaDadosOS"])) { //seleciona os dados da OS no RM na tela views/apontar.php -> inpNumOS
    echo json_postRecuperaDadosOS();

} elseif (isset($_POST["recuperaDadosParteAtiv"])){ //seleciona os dados da Parte e da Atividade no RM na tela views/apontar.php -> selParte e selAtiv
    echo json_postRecuperaDadosParteAtiv();

}elseif (isset($_POST["recuperaSecaoNomeSup"])){ //seleciona os dados de Seção, Descrição, Nome, Chapa e Login dos colaboradores da seção para as telas views/apontar.php -> selSecao e views/gerenciar.php -> selNome
    echo json_postRecuperaSecaoNomeSup();
}elseif (isset($_POST["recuperaApontamento"])){ //seleciona os dados de apontamentos realizados na data
    echo json_postRecuperaApontamento();
}

//-------------------- INICIO FUNÇÕES --------------------

function json_postRecuperaDadosOS() {
    $jsonRetorno = querySelect_dadoOS($_POST["recuperaDadosOS"]["numOS"]);
    echo json_encode($jsonRetorno);
}

function json_postRecuperaDadosParteAtiv() {
    $idSecao = "";
    $json = $_POST["recuperaDadosParteAtiv"];

    //busca no RM o ID da seção baseado no código contido no campo de seção da tela
    if ($json["codTabela"] == 'SECAOPARTE') {
        $result = querySelect_idSecao($json["codInterno"]);
        //adiciona zero à esqueda até estar com 4 dígitos
        for ($i = 0; $i < (4 - strlen($result[0]["ID"])); $i++) {
            $idSecao .= "0";
        }
        $idSecao .= $result[0]["ID"];

    }else{
        $idSecao = $json["codInterno"];
    }

    $jsonRetorno = querySelect_lista($json["codInternoItem"],$json["codTabela"],$idSecao);

    if (count($jsonRetorno) == 0) {
        $jsonRetorno[0]['ITEM'] = 0;
        $jsonRetorno[0]['DESCRICAO'] = 'Nao foi possivel carregar os itens...';
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

?>