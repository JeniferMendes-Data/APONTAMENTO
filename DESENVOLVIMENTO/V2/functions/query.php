<?php
if (session_status() == 1){
    session_start();
}
//*** Funções com querys para interagir com arquivos que precisam de interação com o banco. PADRÃO: nome da função: querySelect_ '+ Objetivo' ou queryInsert_ '+ Objetivo' ou queryUpdate_ '+ Objetivo'
require $_SERVER["DOCUMENT_ROOT"].'/_utilitaries/connect.php';
include_once ($_SERVER["DOCUMENT_ROOT"].'/_utilitaries/config.php');

//Função para permitir acesso ao sistema validando login/ senha e grupo de permissões
function querySelect_login($login, $senha) {
    $stringTipo = "";
    $retorno = querySelect_listaParam();
    foreach ($retorno as $nomePermissao) {
        $stringTipo .= "[".$nomePermissao["TIPO"]."],";
    }
    if (isset($stringTipo)) {
        $stringTipo = substr($stringTipo, 0, -1);
        $query = "SELECT * FROM(SELECT TB_PARAM_RELAC.TIPO, TB_LOGIN.* FROM TB_PARAM_RELAC (NOLOCK) INNER JOIN TB_LOGIN (NOLOCK) ON TB_LOGIN.LOGIN = TB_PARAM_RELAC.ID_USUARIO WHERE TB_PARAM_RELAC.CHAVE = 'PERMISSAO' AND TB_PARAM_RELAC.STATUS_RELAC = 'A' AND TB_LOGIN.STATUS = 'A' AND TB_LOGIN.LOGIN = '".$login."' AND TB_LOGIN.SENHA = '".$senha."') AS TB_PARAM PIVOT( COUNT(TB_PARAM.TIPO) FOR TB_PARAM.TIPO IN (".$stringTipo.")) AS P";
    }

    return realizaConsulta($query);
}

//Função para retornar as tipo de parametros cadastrados no banco
function querySelect_listaParam() {
    $query = "SELECT TIPO FROM TB_PARAM_RELAC (NOLOCK) GROUP BY TIPO";

    return realizaConsulta($query);
}

//Função para selecionar os dados das OS's genéricas - $filtroTmovNumeroMov - Informar OS a ser selecionada
function querySelect_dadoOS($filtroTmovNumeroMov = "") {
    $corporeRM = new Config();
    $query = "select $corporeRM->nomeBaseRM.DBO.TMOV.NUMEROMOV 'TMOV_NUMEROMOV', $corporeRM->nomeBaseRM.DBO.GCCUSTO.NOME 'GCCUSTO_NOME' , ISNULL ($corporeRM->nomeBaseRM.DBO.TMOVCOMPL.DESCRICAOCOMP,'***') 'TMOVCOMPL_DESCRICAOCOMP', GFILIAL.CIDADE 'GFILIAL_CIDADE' from $corporeRM->nomeBaseRM.DBO.TMOV (NOLOCK) INNER JOIN $corporeRM->nomeBaseRM.DBO.GCCUSTO (NOLOCK) ON $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA = $corporeRM->nomeBaseRM.DBO.GCCUSTO.CODCOLIGADA AND $corporeRM->nomeBaseRM.DBO.TMOV.CODCCUSTO = $corporeRM->nomeBaseRM.DBO.GCCUSTO.CODCCUSTO INNER JOIN $corporeRM->nomeBaseRM.DBO.TMOVCOMPL (NOLOCK) ON $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA = $corporeRM->nomeBaseRM.DBO.TMOVCOMPL.CODCOLIGADA AND $corporeRM->nomeBaseRM.DBO.TMOV.IDMOV = $corporeRM->nomeBaseRM.DBO.TMOVCOMPL.IDMOV INNER JOIN $corporeRM->nomeBaseRM.DBO.GFILIAL (NOLOCK) ON $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA = $corporeRM->nomeBaseRM.DBO.GFILIAL.CODCOLIGADA AND $corporeRM->nomeBaseRM.DBO.TMOV.CODFILIAL = $corporeRM->nomeBaseRM.DBO.GFILIAL.CODFILIAL LEFT JOIN $corporeRM->nomeBaseRM.DBO.GCONSIST TMOVCOMPL_STATUSOSCOMPL_DESC (NOLOCK) ON $corporeRM->nomeBaseRM.DBO.TMOVCOMPL.STATUSOSCOMPL = TMOVCOMPL_STATUSOSCOMPL_DESC.CODCLIENTE AND TMOVCOMPL_STATUSOSCOMPL_DESC.CODCOLIGADA = $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA AND TMOVCOMPL_STATUSOSCOMPL_DESC.CODTABELA = 'STATUSOS' where $corporeRM->nomeBaseRM.DBO.TMOV.CODTMV IN ('2.1.01', '2.1.02', '2.1.03', '2.1.04') AND $corporeRM->nomeBaseRM.DBO.TMOV.CODCOLIGADA in ( '1', '2')";
    if ($filtroTmovNumeroMov !== "") {
        $query .= " AND TMOV.NUMEROMOV IN (".$filtroTmovNumeroMov.")";
    }
    return realizaConsulta($query);
}

//Função para selecionar os dados das Partes/ Peças e Atividades -  $codInternoItem = CodTabela a ser retornado; $codTabela = CodTabela do relacionamento; $codInterno = valor da parte ou da seção que será filtrado no where
function querySelect_lista($codInternoItem, $codTabela, $codInterno) {
    $corporeRM = new Config();
    $query = "SELECT SUBSTRING(GCONSIST.CODINTERNO,5,5) 'ITEM', ITEM.DESCRICAO 'DESCRICAO' FROM $corporeRM->nomeBaseRM.DBO.GCONSIST GCONSIST (NOLOCK) INNER JOIN $corporeRM->nomeBaseRM.DBO.GCONSIST ITEM (NOLOCK) ON ITEM.CODTABELA = '".$codInternoItem."' AND ITEM.CODINTERNO = SUBSTRING(GCONSIST.CODINTERNO,5,5) WHERE GCONSIST.CODTABELA = '".$codTabela."' AND SUBSTRING(GCONSIST.CODCLIENTE,0,5) = ".$codInterno;
    return realizaConsulta($query);
}

//Função para retornar o ID da seção baseado no código da mesma
function querySelect_idSecao($codSecao) {
    $corporeRM = new Config();
    $query = "select PSECAO.ID 'ID' from $corporeRM->nomeBaseRM.DBO.PSECAO PSECAO (NOLOCK) where PSECAO.CODIGO = '$codSecao'";
    return realizaConsulta($query);
}

//Função para retornar o ID da seção baseado no código da mesma
function querySelect_causaRetrabalho() {
    $corporeRM = new Config();
    $query = "SELECT CODTABELA, CODINTERNO, DESCRICAO FROM $corporeRM->nomeBaseRM.DBO.GCONSIST GCONSIST (NOLOCK) WHERE CODTABELA = 'RETRABALHO'";
    return realizaConsulta($query);
}

//Função para incluir apontamento na tabela interna log_apontamento
function queryInsert_logApontamento($dados) {
    $base = new Config();
    $query = "INSERT INTO ".$base->connectionInfo["Database"].".[dbo].[LOG_APONTAMENTO] ([N_OS], [ID_USUARIO], [VALIDA], [H_INICIO], [H_FIM], [H_LANCAMENTO], [ORIGEM], [RETRABALHO], [SERV_CAMPO], [PARTE], [ATIVIDADE], [RESP_CRIACAO], [IDMOV]) VALUES ('".$dados["N_OS"]."', '".$dados["ID_USUARIO"]."','".$dados["VALIDA"]."', '".$dados["H_INICIO"]."', '".$dados["H_FIM"]."', '".$dados["H_LANCAMENTO"]."', '".$dados["ORIGEM"]."', '".$dados["RETRABALHO"]."', '".$dados["SERV_CAMPO"]."', '".$dados["PARTE"]."', '".$dados["ATIVIDADE"]."', '".$dados["RESP_CRIACAO"]."', '".$dados["IDMOV"]."')";
    return realizaConsulta($query);
}

//Função para recuperar nome e chapa dos colaboradores da Seção
function querySelect_secaoColaborador($login) {
    $corporeRM = new Config();
    $query = "SELECT TB_PARAM_RELAC.CHAVE 'SECAO' ,TB_LOGIN.NOME 'NOME' ,TB_LOGIN.CHAPA ,TB_LOGIN.PSECAO_DESCRICAO 'SECAO_DESCRICAO' from $corporeRM->nomeBaseRM.DBO.PPESSOA (NOLOCK) INNER JOIN TB_LOGIN (NOLOCK) ON TB_LOGIN.PPESSOA_CODIGO = PPESSOA.CODIGO INNER JOIN TB_PARAM_RELAC (NOLOCK) ON TB_PARAM_RELAC.CHAVE = TB_LOGIN.PFUNC_CODSECAO  LEFT JOIN $corporeRM->nomeBaseRM.DBO.PFUNC (NOLOCK) ON 	$corporeRM->nomeBaseRM.DBO.PPESSOA.CODIGO = $corporeRM->nomeBaseRM.DBO.PFUNC.CODPESSOA 	AND PFUNC.CODSITUACAO <> 'D'  LEFT JOIN $corporeRM->nomeBaseRM.DBO.PEXTERNO (NOLOCK) ON $corporeRM->nomeBaseRM.DBO.PPESSOA.CODIGO = $corporeRM->nomeBaseRM.DBO.PEXTERNO.CODPESSOA AND PEXTERNO.CODSITUACAO <> 'D' WHERE TB_PARAM_RELAC.ID_USUARIO = '".$login."' AND TB_PARAM_RELAC.TIPO = 'APV' AND TB_PARAM_RELAC.CHAVE <> 'PERMISSAO' ORDER BY 1, 2";

    return realizaConsulta($query);
}

//Função para verificar se a chapa do colaborador existe no cadastro de requisitante
function querySelect_codRequisitante($chapa) {
    $base = new Config();
    $query = "SELECT $base->nomeBaseRM.DBO.GCONSIST.CODINTERNO, TB_LOGIN.LOGIN FROM TB_LOGIN INNER JOIN $base->nomeBaseRM.DBO.GCONSIST (NOLOCK) ON $base->nomeBaseRM.DBO.GCONSIST.CODTABELA = 'REQ' AND $base->nomeBaseRM.DBO.GCONSIST.CODINTERNO = TB_LOGIN.CHAPA COLLATE SQL_Latin1_General_CP1_CI_AI WHERE $base->nomeBaseRM.DBO.GCONSIST.CODINTERNO = '".$chapa."'";
    return realizaConsulta($query);
}

//Função para inserir nova liberação de período
/* function queryInsert_tbPeriodo($dataAbertura, $dataFechamento, $login, $periodoIni, $periodoFim, $obs ) {
    $query = "INSERT INTO TB_PERIODO
        	(
        		DT_ABERTURA,
        		DT_FECHAMENTO,
        		STATUS,
        		USR_ABERTURA,
        		USR_FECHAMENTO,
        		PERIODO_INI,
        		PERIODO_FIM,
        		SECAO,
        		OBSERVACAO
        	)
        	VALUES
        	(
        		convert(datetime, '".$dataAbertura."', 103),
        		".$dataFechamento.",
        		'A',
                '".$login."',
        		NULL,
        		".$periodoIni.",
        		".$periodoFim.",
        		'T',
        		".$obs."
        	)";
    printf($query);
} */

//Função para realizar a conexão e o select no banco.
function realizaConsulta($query) {
    $retorno = array();
    $conn = connect_openSql();
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
}
?>