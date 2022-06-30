<?php
    include_once $_SERVER["DOCUMENT_ROOT"].'/functions/query.php';
    include_once $_SERVER["DOCUMENT_ROOT"].'/functions/include.php';
    include_once $_SERVER["DOCUMENT_ROOT"].'/functions/global_functions.php';
    include_once $_SERVER["DOCUMENT_ROOT"].'/_utilitaries/config.php';

    if (session_status() == 1){
        session_start();
    }

    echo include_head('APONTAMENTO | ENVIO');

    //valida condições conforme a tela que enviou
    if ($_GET["pag"] == "login") {
        echo post_getLogin();

    }else if ($_GET["pag"] == "home") {
        echo post_getHome();

    }elseif ($_GET["pag"] == "apontar"){//salva apontamento
        echo post_getApontar();

    }

//-------------------- INICIO FUNÇÕES --------------------
function post_getLogin() {
    //Botão de Acesso do sistema index.php
    if(isset($_POST['acessar'])){
        $login = $_POST['login'];
        $senha = $_POST['senha'];
        $resultado_usuario = querySelect_login($login, $senha);
        if(isset($resultado_usuario) && isset($resultado_usuario[0]['NOME'])){
            $_SESSION['usuarioLogado'] = $resultado_usuario[0]['LOGIN'];
            $_SESSION['nomeUsuario'] = $resultado_usuario[0]['NOME'];
            $_SESSION['chapa'] = $resultado_usuario[0]['CHAPA'];
            $_SESSION['secaoDesc'] = $resultado_usuario[0]['PSECAO_DESCRICAO'];
            $_SESSION['codSecao'] = $resultado_usuario[0]['PFUNC_CODSECAO'];
            $_SESSION['codPessoa'] = $resultado_usuario[0]['PPESSOA_CODIGO'];
            //carrega tipo de permissão
            $tipoPermissao = querySelect_listaParam();
            if(isset($tipoPermissao)){
                foreach ($tipoPermissao as $nomePermissao) {
                    $_SESSION[$nomePermissao["TIPO"]] = $resultado_usuario[0][$nomePermissao["TIPO"]];
                }
            }
            $_SESSION['msg']='sucessoLogin';
            header("Location: ../index.php");
            die();
        }else{
            $_SESSION['msg']='erroLogin';
            $_SESSION['usuario'] = $login;
            header("Location: ../index.php");
            die();
        }
    }else{
        $_SESSION['msg']='erro';
        header("Location: ../index.php");
        die();
    }
}

function post_getHome() {
    //verifica se há liberação de período
    if (isset($_POST["inpLiberarPeriodo"]) && ($_POST["inpLiberarPeriodo"] == "checked")) {
        if (($_POST["inpPerLanIni"] <= $_POST["inpPerLanFim"]) && ($_POST["inpPerRetIni"] <= $_POST["inpPerRetFim"])) {
            $retorno .= "Data Início deve ser menor que a Data Final.";
        }else{
            $retornoInsert = queryInsert_tbPeriodo("A", "B", "C", "D", "E", "F");
            if (count($retornoInsert) == 0) {
                $retorno .= "Dados não foram inseridos no banco, entre em contato com o TI através do GLPI.";
            }else{
                $retorno .= "Período inserido com sucesso.";
            }
        }
    }else{
        $retorno .= "Não há dados para incluir nova liberação de período. Tente novamente.";
    }

    echo '<script>
    $(document).ready(
        function(){
            bootbox.alert(
                {
                message: "'.$retorno.'",
                callback: function(){
                    window.location.href = "/views/home.php";
                    }
                });
            })</script>';
}

function post_getApontar() {
    $config = new Config();

    //converter hora
    $dataFormatInicio = date_format(date_create_from_format('d/m/Y H:i', $_POST["inpDataInicio"]. " ".$_POST["inpHoraInicio"]), 'Y-m-d H:i:s');
    $dataFormatFim = date_format(date_create_from_format('d/m/Y H:i', $_POST["inpDataFim"]. " ".$_POST["inpHoraFim"]), 'Y-m-d H:i:s');

    //informa null em campos vazios
    $inpServCampo = (interna_verificaCampoNull($_POST["inpServCampo"]) == "NULL")?"N":"S";
    $selCausaRetrabalho = (interna_verificaCampoNull($_POST["selCausaRetrabalho"]) == "NULL")?"NA":($_POST["selCausaRetrabalho"]);
    $selParte = interna_verificaCampoNull($_POST["selParte"]);
    $selAtiv = interna_verificaCampoNull($_POST["selAtiv"]);
    $insertBanco = [];
    $codRequisitante = querySelect_codRequisitante($_POST["inpChapa"]);

    printf("selCausaRetrabalho ".$inpServCampo." inpServCampo ".$selCausaRetrabalho);

    //só permite apontamento se houver cadastro de requisitante igual a chapa na CORPORERM.DBO.GCONSIST (CODTABELA = 'REQ')
    if (isset($codRequisitante)) {
        //verifica apontamento de segundo turno
        if (strtotime($dataFormatInicio) > strtotime($dataFormatFim)) {
            array_push($insertBanco,array(
                "N_OS" => $_POST["inpNumOS"],
                "ID_USUARIO" => $codRequisitante[0]["LOGIN"],
                "VALIDA" => ((isset($_SESSION['APV']) && $_SESSION['APV'] == 1)?"A":"P"),
                "H_INICIO" => $dataFormatInicio,
                "H_FIM" => date("Y-m-d", strtotime($dataFormatInicio))." 23:59",
                "H_LANCAMENTO" => date('Y-m-d H:i:s'),
                "ORIGEM" => "APP",
                "RETRABALHO" => $selCausaRetrabalho,
                "SERV_CAMPO" => $inpServCampo,
                "PARTE" => $selParte,
                "ATIVIDADE" => $selAtiv,
                "RESP_CRIACAO" => $_SESSION["usuarioLogado"],
                "IDMOV" => "NULL",
                "CODREQ" => $codRequisitante[0]["CODINTERNO"]
            ));
            array_push($insertBanco,array(
                "N_OS" => $_POST["inpNumOS"],
                "ID_USUARIO" => $codRequisitante[0]["LOGIN"],
                "VALIDA" => ((isset($_SESSION['APV']) && $_SESSION['APV'] == 1)?"A":"P"),
                "H_INICIO" => date("Y-m-d", strtotime($dataFormatFim))." 00:00",
                "H_FIM" => $dataFormatFim,
                "H_LANCAMENTO" => date('Y-m-d H:i:s'),
                "ORIGEM" => "APP",
                "RETRABALHO" => $selCausaRetrabalho,
                "SERV_CAMPO" => $inpServCampo,
                "PARTE" => $selParte,
                "ATIVIDADE" => $selAtiv,
                "RESP_CRIACAO" => $_SESSION["usuarioLogado"],
                "IDMOV" => "NULL",
                "CODREQ" => $codRequisitante[0]["CODINTERNO"]
            ));
        }else{
            array_push($insertBanco,array(
                "N_OS" => $_POST["inpNumOS"],
                "ID_USUARIO" => $codRequisitante[0]["LOGIN"],
                "VALIDA" => ((isset($_SESSION['APV']) && $_SESSION['APV'] == 1)?"A":"P"),
                "H_INICIO" => $dataFormatInicio,
                "H_FIM" => $dataFormatFim,
                "H_LANCAMENTO" => date('Y-m-d H:i:s'),
                "ORIGEM" => "APP",
                "RETRABALHO" => $selCausaRetrabalho,
                "SERV_CAMPO" => $inpServCampo,
                "PARTE" => $selParte,
                "ATIVIDADE" => $selAtiv,
                "RESP_CRIACAO" => $_SESSION["usuarioLogado"],
                "IDMOV" => "NULL",
                "CODREQ" => $codRequisitante[0]["CODINTERNO"]
            ));
        }
        print_r($insertBanco);
        foreach ($insertBanco as $value) {
            if ($value["VALIDA"] == "A") {//envia o apontamento para o RM já aprovado
                $resultRM = interna_enviaRM($value); //ALTERAR XML
                 if (isset($resultRM["ERRO"])) {
                     print_r($resultRM);
                     return "erro";
                 }else{
                     $idMov = str_split($resultRM[1],7);
                     $value["IDMOV"] = $idMov[0];//já grava na base DSS com o IDMOV apontamento do supervisor - Variável estava quebrando valor
                 }
            }

            $resultBancoApontamento = queryInsert_logApontamento($value);//grava o apontamento na base DSS

            if (empty($resultBancoApontamento)) {
                $_SESSION["msg"] = "Apontamento inserido com sucesso!";
            }else{
                $_SESSION["msg"] = "Ocorreu um erro e o apontamento não foi inserido!";
            }

        }
    }else{
        $_SESSION["msg"] = "Não foi possível incluir o apontamento devido a um erro no usuário Requisitante do RM. Favor entrar em contato com o TI.";
    }

    header("Location: ../views/apontar.php");
    die();

}

function interna_verificaCampoNull($campo) {
    printf(" campo ".$campo);
    if ($campo == "" || empty($campo) || $campo == "0") {
        return "NULL";
    }else{
        printf(" entrou no else ".$campo);
        return $campo;
    }
}

//função para montar o XML de envio do apontamento para o RM
function interna_montaXML($campos) {
    $config = new Config();
    $dataHoraAtual = date("Y-m-d H:i:s");

    $xml = simplexml_load_file($_SERVER["DOCUMENT_ROOT"].'/_utilitaries/xml/insere_apontamento.xml'); //carrega modelo de XML de insert

    //$domXML = dom_import_simplexml($xml);
    $xml->TMOV->CODCOLIGADA = $config->coligada;
    $xml->TMOV->CODFILIAL = 1;
    $xml->TMOV->SERIE = 'APT';
    $xml->TMOV->CODTMV = '1.2.04';
    $xml->TMOV->DATAEMISSAO = $campos["H_INICIO"];
    $xml->TMOV->DATAEXTRA1 = $dataHoraAtual;
    $xml->TMOV->USUARIOCRIACAO = $config->usuarioIntegracaoRM;
    $xml->TMOV->DATACRIACAO = $dataHoraAtual;
    $xml->TMOV->RECCREATEDBY = $config->usuarioIntegracaoRM;
    $xml->TMOV->RECCREATEDON = $dataHoraAtual;
    $xml->TMOV->RECMODIFIEDBY = $config->usuarioIntegracaoRM;
    $xml->TMOV->RECMODIFIEDON = $dataHoraAtual;
    $xml->TITMMOV->CODCOLIGADA = $config->coligada;
    $xml->TITMMOV->NSEQITMMOV = 1;
    $xml->TITMMOV->CODFILIAL = 1;
    $xml->TITMMOV->NUMEROSEQUENCIAL = 1;
    $xml->TITMMOV->IDPRD = '4109';
    $xml->TITMMOV->QUANTIDADE = 1;
    $xml->TITMMOV->DATAEMISSAO = $campos["H_INICIO"];
    $xml->TITMMOV->CAMPOLIVRE = $campos["N_OS"];
    $xml->TITMMOV->CODUND = 'H';
    $xml->TITMMOV->RECCREATEDBY =$config->usuarioIntegracaoRM;
    $xml->TITMMOV->RECCREATEDON = $dataHoraAtual;
    $xml->TITMMOV->RECMODIFIEDBY = $config->usuarioIntegracaoRM;
    $xml->TITMMOV->RECMODIFIEDON = $dataHoraAtual;
    $xml->TMOVCOMPL->CODCOLIGADA = $config->coligada;
    $xml->TMOVCOMPL->RECCREATEDBY =$config->usuarioIntegracaoRM;
    $xml->TMOVCOMPL->RECCREATEDON = $dataHoraAtual;
    $xml->TMOVCOMPL->RECMODIFIEDBY = $config->usuarioIntegracaoRM;
    $xml->TMOVCOMPL->RECMODIFIEDON = $dataHoraAtual;
    $xml->TITMMOVCOMPL->CODCOLIGADA = $config->coligada;
    $xml->TITMMOVCOMPL->NSEQITMMOV = 1;
    $xml->TITMMOVCOMPL->REQUISITANTE = $campos["CODREQ"];
    $xml->TITMMOVCOMPL->HORAINICIO = date("H:i", strtotime($campos["H_INICIO"]));
    $xml->TITMMOVCOMPL->HORAFINAL = date("H:i", strtotime($campos["H_FIM"]));
    $xml->TITMMOVCOMPL->RECCREATEDBY =$config->usuarioIntegracaoRM;
    $xml->TITMMOVCOMPL->RECCREATEDON = $dataHoraAtual;
    $xml->TITMMOVCOMPL->RECMODIFIEDBY = $config->usuarioIntegracaoRM;
    $xml->TITMMOVCOMPL->RECMODIFIEDON = $dataHoraAtual;
    $xml->TITMMOVCOMPL->DESCCOMPL = 'APP';
    $xml->TITMMOVCOMPL->NOS = $campos["N_OS"];
    $xml->TITMMOVCOMPL->TIPORETRABALHO = 'NA';
    $xml->TITMMOVCOMPL->SERV_CAMPO = 'N';
    $xml->TITMMOVCOMPL->ATIVIDADE = '0010';
    $xml->TITMMOVCOMPL->PARTE ='0001';
    $xml->TITMMOVCOMPL->RESPONSAVELCOMP = $_SESSION["codPessoa"];

    return $xml;
}

//Função para inserir apontamento no RM
function interna_enviaRM($campos) {
    $xmlEnvioRM = interna_montaXML($campos);
    $config = new Config();

    $soap = new SoapClient("http://d-0174.dataengenharia.bhz:8051/wsDataServer/MEX?wsdl", array("login"=>$config->usuarioIntegracaoRM, "password"=>$config->senhaIntegracaoRM, "trace"=>1));
    $params = array("DataServerName" => "MovMovimentoTBCData", "XML" => $xmlEnvioRM->asXML(), "Contexto" => "CODCOLIGADA=$config->coligada;CODUSUARIO=$config->usuarioIntegracaoRM;CODSISTEMA=T");
    $result = $soap->__soapCall("SaveRecord", array("SaveRecord" => $params));
    $retornoS = $soap->__getLastResponse();

    if (is_soap_fault($result)) {
        $retorno =  array("ERRO" => $result->faultstring);
    }else{
        $retorno = explode(";",$retornoS);
    }

    return $retorno;
}

?>