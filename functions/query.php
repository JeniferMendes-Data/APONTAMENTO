<?php
if (session_status() == 1){
    session_start();
}
//*** Funções com querys para interagir com arquivos que precisam de interação com o banco. PADRÃO: nome da função: querySelect_ '+ Objetivo' ou queryInsert_ '+ Objetivo' ou queryUpdate_ '+ Objetivo'
require $_SERVER["DOCUMENT_ROOT"].'/_utilitaries/connect.php';
include_once ($_SERVER["DOCUMENT_ROOT"].'/_utilitaries/config.php');
include_once ($_SERVER["DOCUMENT_ROOT"].'/functions/global_functions.php');

//Função para permitir acesso ao sistema validando login/ senha e grupo de permissões
function querySelect_login($login, $senha) {
    $stringTipo = "";
    $retorno = querySelect_listaParam();
    foreach ($retorno as $nomePermissao) {
        $stringTipo .= "[".$nomePermissao["TIPO"]."],";
    }
    if (isset($stringTipo)) {
        $stringTipo = substr($stringTipo, 0, -1);
        $query = "SELECT * FROM(SELECT TB_PARAM_RELAC.TIPO, TB_LOGIN.* FROM TB_PARAM_RELAC (NOLOCK) INNER JOIN TB_LOGIN (NOLOCK) ON TB_LOGIN.LOGIN = TB_PARAM_RELAC.ID_USUARIO WHERE TB_PARAM_RELAC.CHAVE = 'PERMISSAO' AND TB_PARAM_RELAC.STATUS_RELAC = 'A' AND TB_LOGIN.STATUS <> 'D' AND TB_LOGIN.LOGIN = '".$login."' AND TB_LOGIN.SENHA = '".$senha."') AS TB_PARAM PIVOT( COUNT(TB_PARAM.TIPO) FOR TB_PARAM.TIPO IN (".$stringTipo.")) AS P";
    }

    return realizaConsulta($query, "querySelect_login");
}

//Função para retornar as tipo de parametros cadastrados no banco
function querySelect_listaParam() {
    $query = "SELECT TIPO FROM TB_PARAM_RELAC (NOLOCK) GROUP BY TIPO";

    return realizaConsulta($query, "querySelect_listaParam");
}

//Função para selecionar os dados das OS's genéricas - $filtroTmovNumeroMov - Informar OS a ser selecionada
function querySelect_dadoOS($filtroTmovNumeroMov = "") {
    $corporeRM = new Config();
    $query = "select $corporeRM->nomeBaseRM.DBO.TMOV.NUMEROMOV 'TMOV_NUMEROMOV', $corporeRM->nomeBaseRM.DBO.GCCUSTO.NOME 'GCCUSTO_NOME' , ISNULL ($corporeRM->nomeBaseRM.DBO.TMOVCOMPL.DESCRICAOCOMP,'***') 'TMOVCOMPL_DESCRICAOCOMP', GFILIAL.CIDADE 'GFILIAL_CIDADE', $corporeRM->nomeBaseRM.DBO.TMOV.CODFILIAL 'CODFILIAL', $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA 'CODCOLIGADA' from $corporeRM->nomeBaseRM.DBO.TMOV (NOLOCK) INNER JOIN $corporeRM->nomeBaseRM.DBO.GCCUSTO (NOLOCK) ON $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA = $corporeRM->nomeBaseRM.DBO.GCCUSTO.CODCOLIGADA AND $corporeRM->nomeBaseRM.DBO.TMOV.CODCCUSTO = $corporeRM->nomeBaseRM.DBO.GCCUSTO.CODCCUSTO INNER JOIN $corporeRM->nomeBaseRM.DBO.TMOVCOMPL (NOLOCK) ON $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA = $corporeRM->nomeBaseRM.DBO.TMOVCOMPL.CODCOLIGADA AND $corporeRM->nomeBaseRM.DBO.TMOV.IDMOV = $corporeRM->nomeBaseRM.DBO.TMOVCOMPL.IDMOV INNER JOIN $corporeRM->nomeBaseRM.DBO.GFILIAL (NOLOCK) ON $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA = $corporeRM->nomeBaseRM.DBO.GFILIAL.CODCOLIGADA AND $corporeRM->nomeBaseRM.DBO.TMOV.CODFILIAL = $corporeRM->nomeBaseRM.DBO.GFILIAL.CODFILIAL LEFT JOIN $corporeRM->nomeBaseRM.DBO.GCONSIST TMOVCOMPL_STATUSOSCOMPL_DESC (NOLOCK) ON $corporeRM->nomeBaseRM.DBO.TMOVCOMPL.STATUSOSCOMPL = TMOVCOMPL_STATUSOSCOMPL_DESC.CODCLIENTE AND TMOVCOMPL_STATUSOSCOMPL_DESC.CODCOLIGADA = $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA AND TMOVCOMPL_STATUSOSCOMPL_DESC.CODTABELA = 'STATUSOS' where $corporeRM->nomeBaseRM.DBO.TMOV.CODTMV IN ('2.1.01', '2.1.02', '2.1.03', '2.1.04') AND $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA in ( '1', '2') AND $corporeRM->nomeBaseRM.DBO.TMOV.SERIE = 'OS' AND $corporeRM->nomeBaseRM.DBO.TMOVCOMPL.STATUSOSCOMPL IN ($corporeRM->StatusOSAtv) AND $corporeRM->nomeBaseRM.DBO.TMOV.NUMEROMOV NOT IN ($corporeRM->OSBloqueada)";
    if ($filtroTmovNumeroMov !== "") {
        $query .= " AND TMOV.NUMEROMOV IN (".$filtroTmovNumeroMov.")";
    }
    return realizaConsulta($query, "querySelect_dadoOS");
}

//Função para selecionar os dados das Partes/ Peças e Atividades
function querySelect_lista($codSecao) {
    $corporeRM = new Config();
    $query = "SELECT SUBSTRING(SECAOPARTE.CODINTERNO,5,5) 'ITEM', SUBSTRING(PARTEATIV.CODINTERNO,0,5) 'PARTE', PARTE.DESCRICAO 'DESCRICAO_PARTE', SUBSTRING(PARTEATIV.CODINTERNO,5,5) 'ATIV', ATIVIDADE.DESCRICAO 'DESCRICAO_ATIV', SUBSTRING(SECAOPARTE.CODINTERNO,0,5) 'CODSECAO' FROM $corporeRM->nomeBaseRM.DBO.GCONSIST SECAOPARTE (NOLOCK)  INNER JOIN $corporeRM->nomeBaseRM.DBO.GCONSIST PARTEATIV (NOLOCK) ON PARTEATIV.CODTABELA = 'PARTEATIV' AND PARTEATIV.DESCRICAO = SUBSTRING(SECAOPARTE.CODINTERNO,5,5) LEFT JOIN $corporeRM->nomeBaseRM.DBO.GCONSIST PARTE (NOLOCK) ON PARTE.CODTABELA = 'PARTE' AND PARTE.CODCLIENTE = SUBSTRING(PARTEATIV.CODINTERNO,0,5) LEFT JOIN $corporeRM->nomeBaseRM.DBO.GCONSIST ATIVIDADE (NOLOCK) ON ATIVIDADE.CODTABELA = 'ATIVIDADE' AND ATIVIDADE.CODCLIENTE = SUBSTRING(PARTEATIV.CODINTERNO,5,5)  WHERE SECAOPARTE.CODTABELA = 'SECAOPARTE' AND SUBSTRING(SECAOPARTE.CODCLIENTE,0,5) IN (".$codSecao.")";
    return realizaConsulta($query, "querySelect_lista");
}

//Função para retornar os dados da seção baseado no código da mesma
function querySelect_idSecao($codSecao) {
    $corporeRM = new Config();
    $query = "select PSECAO.ID 'ID', PSECAO.CODCOLIGADA 'CODCOLIGADA', PSECAO.CODFILIAL 'CODFILIAL' from $corporeRM->nomeBaseRM.DBO.PSECAO PSECAO (NOLOCK) where PSECAO.CODIGO = '$codSecao' AND PSECAO.CODCOLIGADA = ".$_SESSION['coligada'];
    return realizaConsulta($query, "querySelect_idSecao");
}

//Função para retornar a causa do Retrabalho
function querySelect_causaRetrabalho() {
    $corporeRM = new Config();
    $query = "SELECT CODTABELA, CODINTERNO, DESCRICAO FROM $corporeRM->nomeBaseRM.DBO.GCONSIST GCONSIST (NOLOCK) WHERE CODTABELA = 'RETRABALHO'";
    return realizaConsulta($query, "querySelect_causaRetrabalho");
}

//Função para incluir apontamento na tabela interna log_apontamento
function queryInsert_logApontamento($dados, $conn) {
    $base = new Config();
    $query = "INSERT INTO ".$base->connectionInfo["Database"].".[dbo].[LOG_APONTAMENTO] ([N_OS], [ID_USUARIO], [VALIDA], [H_INICIO], [H_FIM], [H_LANCAMENTO], [ORIGEM], [RETRABALHO], [SERV_CAMPO], [PARTE], [ATIVIDADE], [RESP_CRIACAO], [IDMOV], [SECAO_APONT], [OBS], [RESP_APV]) VALUES ('".$dados["N_OS"]."', '".$dados["ID_USUARIO"]."','".$dados["VALIDA"]."', '".$dados["H_INICIO"]."', '".$dados["H_FIM"]."', '".$dados["H_LANCAMENTO"]."', '".$dados["ORIGEM"]."', '".$dados["RETRABALHO"]."', '".$dados["SERV_CAMPO"]."', '".$dados["PARTE"]."', '".$dados["ATIVIDADE"]."', '".$dados["RESP_CRIACAO"]."', '".$dados["IDMOV"]."', '".$dados["SECAO_APONT"]."', '".$dados["OBS"]."', '".$dados["RESP_APV"]."')";
    return realizaConsulta($query, "queryInsert_logApontamento", $conn);
}

//Função para incluir apontamento na tabela interna log_apontamento
function queryUpdate_logApontamento($dados, $conn) {
    $base = new Config();
    $query = "UPDATE ".$base->connectionInfo["Database"].".[dbo].[LOG_APONTAMENTO] SET [N_OS] = '".$dados["N_OS"]."', [ID_USUARIO] = '".$dados["ID_USUARIO"]."', [VALIDA] = '".$dados["VALIDA"]."', [H_INICIO] = '".$dados["H_INICIO"]."', [H_FIM] = '".$dados["H_FIM"]."', [H_LANCAMENTO] = '".$dados["H_LANCAMENTO"]."', [ORIGEM] = '".$dados["ORIGEM"]."', [RETRABALHO] = '".$dados["RETRABALHO"]."', [SERV_CAMPO] = '".$dados["SERV_CAMPO"]."', [PARTE] = '".$dados["PARTE"]."', [ATIVIDADE] = '".$dados["ATIVIDADE"]."', [IDMOV] = '".$dados["IDMOV"]."', [SECAO_APONT] = '".$dados["SECAO_APONT"]."', [OBS] = '".$dados["OBS"]."', [RESP_APV] = '".$dados["RESP_APV"]."' WHERE [ID_APONTAMENTO] = '".$dados["ID_APONTAMENTO"]."'";
    return realizaConsulta($query, "queryUpdate_logApontamento", $conn);
}

//Função para recuperar nome e chapa dos colaboradores da Seção
function querySelect_secaoColaborador($login) {
    $corporeRM = new Config();
    $query = "SELECT TB_PARAM_RELAC.CHAVE 'SECAO' ,TB_LOGIN.NOME 'NOME' ,TB_LOGIN.CHAPA ,TB_LOGIN.PSECAO_DESCRICAO 'SECAO_DESCRICAO', TB_LOGIN.LOGIN from TB_LOGIN (NOLOCK) INNER JOIN TB_PARAM_RELAC (NOLOCK) ON TB_PARAM_RELAC.CHAVE = TB_LOGIN.PFUNC_CODSECAO WHERE TB_PARAM_RELAC.ID_USUARIO = '".$login."' AND TB_PARAM_RELAC.TIPO = 'APV' AND TB_PARAM_RELAC.CHAVE <> 'PERMISSAO' AND TB_LOGIN.STATUS <> 'D' AND TB_LOGIN.PSECAO_CODCOLIGADA = ".$_SESSION['coligada']." AND TB_LOGIN.PFUNC_CODEQUIPE IN (SELECT CHAVE FROM TB_PARAM_RELAC WHERE ID_USUARIO = '".$login."' AND TIPO = 'APV_EQP') ORDER BY 1, 2";

    return realizaConsulta($query, "querySelect_secaoColaborador");
}

//Função para verificar se a chapa do colaborador existe no cadastro de requisitante
function querySelect_codRequisitante($chapa) {
    $base = new Config();
    $query = "SELECT $base->nomeBaseRM.DBO.GCONSIST.CODINTERNO, TB_LOGIN.LOGIN FROM TB_LOGIN INNER JOIN $base->nomeBaseRM.DBO.GCONSIST (NOLOCK) ON $base->nomeBaseRM.DBO.GCONSIST.CODTABELA = 'REQ' AND $base->nomeBaseRM.DBO.GCONSIST.CODINTERNO = TB_LOGIN.CHAPA COLLATE SQL_Latin1_General_CP1_CI_AI WHERE $base->nomeBaseRM.DBO.GCONSIST.CODINTERNO = '".$chapa."'";
    return realizaConsulta($query, "querySelect_codRequisitante");
}

//Função para carregar os dados dos apontamentos
function querySelect_buscaApontamento($idUsuario, $data, $srvCampo = 'N') {
    $base = new Config();
    if ($srvCampo == 'S') { //SUPERVISOR SERVICO DE CAMPO
        $campo = "OR LOG_APONTAMENTO.SERV_CAMPO = 'S'";
    }elseif ($srvCampo == 'N'){//SUPERVISOR INTERNO
        $campo = "AND LOG_APONTAMENTO.SERV_CAMPO = 'N'";
    }else{//COLABORADOR
        $campo = "";
    }
    $query = "SELECT LOG_APONTAMENTO.*, TB_LOGIN.NOME, TB_LOGIN.PSECAO_DESCRICAO, PARTE.DESCRICAO 'PARTE_DESC', ATIVIDADE.DESCRICAO 'ATIV_DESC' FROM LOG_APONTAMENTO (NOLOCK) INNER JOIN TB_LOGIN ON TB_LOGIN.LOGIN = LOG_APONTAMENTO.ID_USUARIO LEFT JOIN $base->nomeBaseRM.DBO.GCONSIST PARTE ON PARTE.CODTABELA = 'PARTE' AND PARTE.CODCLIENTE = LOG_APONTAMENTO.PARTE COLLATE SQL_Latin1_General_CP1_CI_AS LEFT JOIN $base->nomeBaseRM.DBO.GCONSIST ATIVIDADE ON ATIVIDADE.CODTABELA = 'ATIVIDADE' AND ATIVIDADE.CODCLIENTE = LOG_APONTAMENTO.ATIVIDADE COLLATE SQL_Latin1_General_CP1_CI_AS WHERE (ID_USUARIO IN (".$idUsuario.")".$campo.") AND CONVERT(date,H_INICIO,103)='".$data."'";
    return realizaConsulta($query, "querySelect_buscaApontamento");
}

//Função para alterar o status do apontamento
function querySelect_buscaChapaColaborador($login){
    $query = "SELECT TB_LOGIN.CHAPA from TB_LOGIN (NOLOCK) WHERE TB_LOGIN.LOGIN = '".$login."'";

    return realizaConsulta($query, "querySelect_buscaChapaColaborador");
}

//Função para selecionar apontamento para update
function querySelect_buscaApontamentoID($id){
    $query = "SELECT LOG_APONTAMENTO.* FROM LOG_APONTAMENTO (NOLOCK) WHERE ID_APONTAMENTO = ".$id;
    return realizaConsulta($query, "querySelect_buscaApontamentoID");
}

//Função para verificar se existe apontamento para o intervalo de horas informado
function querySelect_checaIntervalo($hraIni, $hraFim, $login, $id){
    $query = "SELECT COUNT(*) 'TOTAL' FROM LOG_APONTAMENTO WHERE ((H_INICIO BETWEEN '$hraIni' AND '$hraFim' OR H_FIM BETWEEN '$hraIni' AND '$hraFim') OR ('$hraIni' BETWEEN H_INICIO AND H_FIM OR '$hraFim' BETWEEN H_INICIO AND H_FIM)) AND ID_USUARIO = '$login' AND ID_APONTAMENTO NOT IN ('$id') AND VALIDA <> 'R'";
    return realizaConsulta($query, "querySelect_checaIntervalo");
}

//Função para realizar a conexão e o select no banco.
function realizaConsulta($query, $origem, $conn = false) {
    try{
        global_geraLog(("BANCO: FUNCTION: ".$origem." QUERY: ".$query." "), "info");
        $retorno = array();
        
        if($conn == false){
            $conn = connect_openSql();            
            if (!$conn) {                
                throw new Exception("Conexão com o banco não estabelecida", 1);            
                die( print_r( sqlsrv_errors(), true));
            }
            $fecharConn = true;
        }
        $result = sqlsrv_query($conn, $query);

        if ($result === false) {
            print_r(sqlsrv_errors());
            if( ($errors = sqlsrv_errors() ) != null) {
            return $errors["message"];
            }
        }else if (sqlsrv_has_rows($result)) {
            while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
            {
                array_push($retorno, $row);
            }
            return $retorno;
        }
    }catch(\Throwable $e){
        echo $e->getMessage();
    }finally{
        if ($fecharConn) {
            connect_closeSql($conn);
        }
    }    
}

//função para iniciar um bloco de transações
function query_beginTransaction(){
    try {
        $conn = connect_openSql();
        if ($conn) {
            sqlsrv_begin_transaction($conn);
            return $conn;
        }else{
            throw new Exception("Conexão com o banco não estabelecida", 1);            
        }
    } catch (\Throwable $e) {
        echo $e->getMessage();
    }    
}

//função para commit de insert ou update
function query_commitTransaction($conn){
    try {
        if ($conn) {
            $fecharConn = true;
            return sqlsrv_commit($conn);
        }else{
            throw new Exception("Conexão com o banco não estabelecida", 1);
        }
    }catch (\Throwable $e) {
        echo $e->getMessage();
    }finally{
        if ($fecharConn) {
            connect_closeSql($conn);
        }
    }  
}

//função para rollback de insert ou update
function query_rollbackTransaction($conn){
    try {
        if ($conn) {
            $fecharConn = true;
            return sqlsrv_rollback($conn);
        }else{
            throw new Exception("Conexão com o banco não estabelecida", 1);
        }
    }catch (\Throwable $e) {
        echo $e->getMessage();
    }finally{
        if ($fecharConn) {
            connect_closeSql($conn);
        }
    }  
}
?>