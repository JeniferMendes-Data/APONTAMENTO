<?php
    include_once $_SERVER["DOCUMENT_ROOT"].'/functions/query.php';
    include_once $_SERVER["DOCUMENT_ROOT"].'/functions/include.php';
    include_once $_SERVER["DOCUMENT_ROOT"].'/functions/global_functions.php';
    include_once $_SERVER["DOCUMENT_ROOT"].'/_utilitaries/config.php';

    if (session_status() == 1){
        session_start();
    }

    //echo include_head('APONTAMENTO | ENVIO');

    //valida condições conforme a tela que enviou
    if ($_GET["pag"] == "login") {
        echo post_getLogin();

    }else if ($_GET["pag"] == "home") {
        echo post_getHome();

    }elseif ($_GET["pag"] == "apontar"){//salva apontamento
        echo post_getApontar("NULL", "");

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
            $_SESSION['codEquipe'] = $resultado_usuario[0]['PFUNC_CODEQUIPE'];
            $_SESSION['coligada'] = $resultado_usuario[0]['PSECAO_CODCOLIGADA'];
            $_SESSION['filial'] = $resultado_usuario[0]['PSECAO_CODFILIAL'];
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

    echo '<script>$(document).ready(function(){bootbox.alert({message: "'.$retorno.'",callback: function(){window.location.href = "/views/home.php";}});})</script>';
}

function post_getApontar($idApont, $valida, $valoresEdit = "") {
    try {
        $config = new Config();    
        $insertBanco = [];       

        if ($idApont == "NULL") {//apontar novo
            $valida = ((isset($_SESSION['APV']) && $_SESSION['APV'] == 1)?"A":"P");
            //converter hora
            $dataFormatInicio = date_format(date_create_from_format('d/m/Y H:i:s', $_POST["inpDataInicio"]. " ".$_POST["inpHoraInicio"].":02"), 'Y-m-d H:i:s');
            $dataFormatFim = date_format(date_create_from_format('d/m/Y H:i:s', $_POST["inpDataFim"]. " ".$_POST["inpHoraFim"].":01"), 'Y-m-d H:i:s');

            //informa null em campos vazios
            $inpServCampo = (interna_verificaCampoNull($_POST["inpServCampo"]) == "NULL")?"N":$_POST["inpServCampo"];
            $selCausaRetrabalho = (interna_verificaCampoNull($_POST["selCausaRetrabalho"]) == "NULL")?"NA":($_POST["selCausaRetrabalho"]);
            $selParte = interna_verificaCampoNull($_POST["selParte"]);
            $selAtiv = interna_verificaCampoNull($_POST["selAtiv"]);
            $observacao = interna_verificaCampoNull($_POST["observacao"]);
            $inpSecao = interna_verificaCampoNull($_POST["inpSecao"]);
            $inpNumOs = $_POST["inpNumOS"];
            $chapa = $_POST["inpChapa"];
            $hLancamento = date('Y-m-d H:i:s');
            $resp_apv = ((isset($_SESSION['APV']) && $_SESSION['APV'] == 1)?$_SESSION['usuarioLogado']:"NULL");
        }elseif ($valida == 'E'){ //editar
            $apont = querySelect_buscaApontamentoID($idApont);
            if (!isset($apont)) {//apontamento não encontrado
                throw new Exception("Não foi possível Editar esse apontamento");
            }elseif ($apont[0]["VALIDA"] == "A" && $valoresEdit["VALIDA"] == "A") {//apontamento já aprovado            
                throw new Exception("Apontamento já aprovado");
            }
            $chapa = querySelect_buscaChapaColaborador($apont[0]["ID_USUARIO"]);
            $chapa = $chapa[0]["CHAPA"];
            $inpNumOs = $valoresEdit["inpNumOS"];
            $inpServCampo = (interna_verificaCampoNull($valoresEdit["inpServCampo"]) == "NULL")?"N":$valoresEdit["inpServCampo"];
            $selCausaRetrabalho = (interna_verificaCampoNull($valoresEdit["selCausaRetrabalho"]) == "NULL")?"NA":($valoresEdit["selCausaRetrabalho"]);
            $dataFormatInicio = date_format(date_create_from_format('d/m/Y H:i:s', $valoresEdit["inpDataInicio"]. " ".$valoresEdit["inpHoraInicio"].":02"), 'Y-m-d H:i:s');
            $dataFormatFim = date_format(date_create_from_format('d/m/Y H:i:s', $valoresEdit["inpDataInicio"]. " ".$valoresEdit["inpHoraFim"].":01"), 'Y-m-d H:i:s');
            $selParte = interna_verificaCampoNull($valoresEdit["selParte"]);
            $selAtiv = interna_verificaCampoNull($valoresEdit["selAtiv"]);
            $observacao = interna_verificaCampoNull($valoresEdit["inpObs"]);
            $inpSecao = $apont[0]["SECAO_APONT"];
            $hLancamento = date_format($apont[0]["H_LANCAMENTO"], 'Y-m-d H:i:s');
            $resp_apv = ((isset($_SESSION['APV']) && $_SESSION['APV'] == 1)?$_SESSION['usuarioLogado']:"NULL");
        }else{ //aprovar_reprovar
            $apont = querySelect_buscaApontamentoID($idApont);
            if (!isset($apont)) {//apontamento não encontrado
                throw new Exception("Não foi possível Aprovar/Reprovar esse apontamento");
            } elseif ($apont[0]["VALIDA"] == "A") {//apontamento já aprovado            
                throw new Exception("Apontamento já aprovado");
            }
            $dataFormatInicio = date_format($apont[0]["H_INICIO"], 'Y-m-d H:i:s');
            $dataFormatFim = date_format($apont[0]["H_FIM"], 'Y-m-d H:i:s');
            $inpServCampo = $apont[0]["SERV_CAMPO"];
            $selCausaRetrabalho = $apont[0]["RETRABALHO"];
            $selParte = $apont[0]["PARTE"];
            $selAtiv = $apont[0]["ATIVIDADE"];
            $observacao = $apont[0]["OBS"];
            $inpSecao = $apont[0]["SECAO_APONT"];
            $inpNumOs = $apont[0]["N_OS"];        
            $chapa = querySelect_buscaChapaColaborador($apont[0]["ID_USUARIO"]);
            $chapa = $chapa[0]["CHAPA"];
            $hLancamento = date_format($apont[0]["H_LANCAMENTO"], 'Y-m-d H:i:s');
            $resp_apv = $_SESSION['usuarioLogado'];
        }

        //recupera coligada e filial da secao do apontamento
        $origemSecao = querySelect_idSecao($inpSecao);
        
        $codRequisitante = querySelect_codRequisitante($chapa);
        //só permite apontamento se houver cadastro de requisitante igual a chapa na CORPORERM.DBO.GCONSIST (CODTABELA = 'REQ')
        if (isset($codRequisitante)) {//monta array com os valores
            array_push($insertBanco,array(
                "IDMOV" => -1,
                "NUMEROMOV" => -1,
                "N_OS" => $inpNumOs,
                "ID_USUARIO" => $codRequisitante[0]["LOGIN"],
                "VALIDA" => $valida,
                "H_INICIO" => $dataFormatInicio,
                "H_FIM" => $dataFormatFim,
                "H_LANCAMENTO" => $hLancamento,
                "ORIGEM" => "APP",
                "RETRABALHO" => $selCausaRetrabalho,
                "SERV_CAMPO" => $inpServCampo,
                "PARTE" => $selParte,
                "ATIVIDADE" => $selAtiv,
                "RESP_CRIACAO" => $_SESSION["usuarioLogado"],
                "OBS" => $observacao,
                "SECAO_APONT" => $inpSecao,
                "CODREQ" => $codRequisitante[0]["CODINTERNO"],
                "ID_APONTAMENTO" => $idApont,
                "CODFILIAL" => $origemSecao[0]["CODFILIAL"],
                "CODCOLIGADA" => $origemSecao[0]["CODCOLIGADA"],
                "RESP_APV" => $resp_apv
            ));

            //manipula o banco 
            $conn = query_beginTransaction(); //abre a transação
            if ($conn) {
                if (isset($apont)) {//edita/aprova apontamento              
                    if ($valida == "E" && $valoresEdit["VALIDA"] == "M") {//editar sem atualizar status
                        $insertBanco[0]["VALIDA"] = $apont[0]["VALIDA"];
                    }else if ($valida == "A" || ($valida == "E" && $valoresEdit["VALIDA"] == "A")){//só envia para o RM apontamento Aprovado
                        $insertBanco[0]["VALIDA"] = "A";
                        if ($apont[0]["VALIDA"] == "A"){//apontamento aprovado
                            //update log_apontamento, updateRM
                            $insertBanco[0]["IDMOV"] = $apont[0]["IDMOV"];
                        }
                        $resultRM = interna_enviaRM($insertBanco[0]);
                        if (!isset($resultRM["ERRO"])) {
                            $idMov = str_split($resultRM[1],7);
                            $insertBanco[0]["IDMOV"] = $idMov[0];//confirma o IDMOV para enviar para a base do apontamento
                        }
                    }                   

                    $resultBancoApontamento = queryUpdate_logApontamento($insertBanco[0], $conn);//grava o apontamento na base DSS

                    if (empty($resultBancoApontamento)) {
                        query_commitTransaction($conn);
                        return true;
                    }else{
                        throw new Exception("Não foi possível concluir. Dados inconsistentes! Entre em contato com a TI.", 2);
                    }
                }else{ //incluir novo
                    if ($insertBanco[0]["VALIDA"] == "A") {//envia o apontamento para o RM já aprovado
                        $resultRM = interna_enviaRM($insertBanco[0]); 
                        if (!isset($resultRM["ERRO"])) {
                            $idMov = str_split($resultRM[1],7);
                            $insertBanco[0]["IDMOV"] = $idMov[0];//já grava na base do apontamento com o IDMOV
                        }                   
                    }
                    $resultBancoApontamento = queryInsert_logApontamento($insertBanco[0], $conn);//grava o apontamento na base DSS

                    if (empty($resultBancoApontamento)) {
                        query_commitTransaction($conn);
                        $_SESSION["msg"] = "Apontamento inserido com sucesso!";
                    }else{
                        $_SESSION["msg"] = "Ocorreu um erro e o apontamento não foi inserido!";
                    }

                    header("Location: ../views/apontar.php");
                    die();
                }
            }else{
                query_rollbackTransaction($conn);
                throw new Exception("Não foi possível concluir. Conexão com o banco não estabelecida");    
            }          
        }else{
            throw new Exception("Não foi possível concluir devido a um erro no usuário Requisitante do RM. Favor entrar em contato com a TI.");
        }  
    } catch (\Throwable $e) {
        if ($e->getCode() == 2) {//erro fatal necessário rollback
            query_rollbackTransaction($conn);
        }
        echo $e->getMessage();
    }
}

function interna_verificaCampoNull($campo) {
    if ($campo == "" || empty($campo) || $campo == "0") {
        return "NULL";
    }else{
        return $campo;
    }
}

//função para montar o XML de envio do apontamento para o RM
function interna_montaXML($campos) {
    $config = new Config();
    $dataHoraAtual = date("Y-m-d H:i:s");

    $xml = simplexml_load_file($_SERVER["DOCUMENT_ROOT"].'/_utilitaries/xml/insere_apontamento.xml'); //carrega modelo de XML de insert
    
    $xml->TMOV->IDMOV = $campos["IDMOV"];
    $xml->TMOV->NUMEROMOV = $campos["NUMEROMOV"];
    $xml->TMOV->CODCOLIGADA = 1;
    $xml->TMOV->CODFILIAL = $campos["CODFILIAL"];
    $xml->TMOV->CODLOC = "01";
    $xml->TMOV->SERIE = '----';
    $xml->TMOV->CODTMV = '1.2.04';
    $xml->TMOV->DATAEMISSAO = $campos["H_INICIO"];
    $xml->TMOV->DATAEXTRA1 = $campos["H_LANCAMENTO"];
    $xml->TMOV->USUARIOCRIACAO = $config->usuarioIntegracaoRM;
    $xml->TMOV->DATACRIACAO = $campos["H_LANCAMENTO"];
    $xml->TMOV->RECCREATEDBY = $config->usuarioIntegracaoRM;
    $xml->TMOV->RECCREATEDON = $campos["H_LANCAMENTO"];
    $xml->TMOV->RECMODIFIEDBY = $config->usuarioIntegracaoRM;
    $xml->TMOV->RECMODIFIEDON = $dataHoraAtual;
    $xml->TITMMOV->IDMOV = $campos["IDMOV"];
    $xml->TITMMOV->CODCOLIGADA = 1;
    $xml->TITMMOV->NSEQITMMOV = 1;
    $xml->TITMMOV->CODFILIAL = $campos["CODFILIAL"];
    $xml->TITMMOV->NUMEROSEQUENCIAL = 1;
    $xml->TITMMOV->IDPRD = '4109';
    $xml->TITMMOV->QUANTIDADE = 1;
    $xml->TITMMOV->DATAEMISSAO = $campos["H_INICIO"];
    $xml->TITMMOV->CAMPOLIVRE = $campos["N_OS"];
    $xml->TITMMOV->CODUND = 'H';
    $xml->TITMMOV->RECCREATEDBY =$config->usuarioIntegracaoRM;
    $xml->TITMMOV->RECCREATEDON = $campos["H_LANCAMENTO"];
    $xml->TITMMOV->RECMODIFIEDBY = $config->usuarioIntegracaoRM;
    $xml->TITMMOV->RECMODIFIEDON = $dataHoraAtual;
    $xml->TMOVCOMPL->IDMOV = $campos["IDMOV"];
    $xml->TMOVCOMPL->CODCOLIGADA = 1;
    $xml->TMOVCOMPL->RECCREATEDBY =$config->usuarioIntegracaoRM;
    $xml->TMOVCOMPL->RECCREATEDON = $campos["H_LANCAMENTO"];
    $xml->TMOVCOMPL->RECMODIFIEDBY = $config->usuarioIntegracaoRM;
    $xml->TMOVCOMPL->RECMODIFIEDON = $dataHoraAtual;
    $xml->TITMMOVCOMPL->IDMOV = $campos["IDMOV"];
    $xml->TITMMOVCOMPL->CODCOLIGADA = 1;
    $xml->TITMMOVCOMPL->NSEQITMMOV = 1;
    $xml->TITMMOVCOMPL->REQUISITANTE = $campos["CODREQ"];
    $xml->TITMMOVCOMPL->HORAINICIO = date("H:i", strtotime($campos["H_INICIO"]));
    $xml->TITMMOVCOMPL->HORAFINAL = date("H:i", strtotime($campos["H_FIM"]));
    $xml->TITMMOVCOMPL->RECCREATEDBY =$config->usuarioIntegracaoRM;
    $xml->TITMMOVCOMPL->RECCREATEDON = $campos["H_LANCAMENTO"];
    $xml->TITMMOVCOMPL->RECMODIFIEDBY = $config->usuarioIntegracaoRM;
    $xml->TITMMOVCOMPL->RECMODIFIEDON = $dataHoraAtual;
    $xml->TITMMOVCOMPL->DESCCOMPL = $campos["OBS"];
    $xml->TITMMOVCOMPL->NOS = substr($campos["N_OS"], -6);
    $xml->TITMMOVCOMPL->TIPORETRABALHO = $campos["RETRABALHO"];
    $xml->TITMMOVCOMPL->SERV_CAMPO = $campos["SERV_CAMPO"];
    $xml->TITMMOVCOMPL->ATIVIDADE = $campos["ATIVIDADE"];
    $xml->TITMMOVCOMPL->PARTE =$campos["PARTE"];
    $xml->TITMMOVCOMPL->RESPONSAVELCOMP = $_SESSION["codPessoa"];

    global_geraLog(("XML: FUNCTION: interna_montaXML VALOR: ".print_r($xml)." "), "info");
    return $xml;
}

//Função para inserir apontamento no RM
function interna_enviaRM($campos) {
    $xmlEnvioRM = interna_montaXML($campos);
    $config = new Config();
    $enderecoConexao = $config->enderecoSOAP;
    $enderecoConexao .= "wsDataServer/MEX?wsdl";
    $coligada = $campos["CODCOLIGADA"];


    $soap = new SoapClient($config->enderecoSOAP."wsDataServer/MEX?wsdl", array("login"=>$config->usuarioIntegracaoRM, "password"=>$config->senhaIntegracaoRM, "trace"=>1));
    $params = array("DataServerName" => "MovMovimentoTBCData", "XML" => $xmlEnvioRM->asXML(), "Contexto" => "CODCOLIGADA=$coligada;CODUSUARIO=$config->usuarioIntegracaoRM;CODSISTEMA=T");
    //refatorar
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