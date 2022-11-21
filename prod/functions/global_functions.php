<?php
if (session_status() == 1){
    session_start();
}
//*** Funções com interação genérica (post e/ou get) com as views. PADRÃO: nome da função: global_ '+ Objetivo'

include_once $_SERVER["DOCUMENT_ROOT"].'/functions/query.php';

function global_geraLog($msg, $tipo, $file="") {
    // variável que vai armazenar o nível do log (INFO, WARNING ou ERROR)
    $levelStr = '';
    $file = $_SERVER["DOCUMENT_ROOT"].'/_utilitaries/log.txt';

    //backup de arquivos
    if (date('Y-m-d', fileatime($file)) < date('Y-m-d')){
        rename($_SERVER["DOCUMENT_ROOT"].'/_utilitaries/log.txt', $_SERVER["DOCUMENT_ROOT"].'/_utilitaries/bkp_log/log_'.date('Y-m-d', fileatime($file)).'.txt');
    }    

    // verifica o tipo de log
    switch ($tipo)
    {
        case 'info':
            // nível de informação
            $levelStr = 'INFO';
            break;

        case 'warning':
            // nível de aviso
            $levelStr = 'WARNING';
            break;

        case 'error':
            // nível de erro
            $levelStr = 'ERROR';
            break;
    }

    // data atual
    date_default_timezone_set('America/Sao_Paulo');
    $date = date('Y-m-d H:i:s');

    // formata a mensagem do log
    // 1o: data atual
    // 2o: nível da mensagem (INFO, WARNING ou ERROR)
    // 3o: a mensagem propriamente dita
    // 4o: uma quebra de linha
    $msg = sprintf( "[%s] [%s]: %s%s", $date, $levelStr, $msg, PHP_EOL );
    // escreve o log no arquivo
    // é necessário usar FILE_APPEND para que a mensagem seja escrita no final do arquivo, preservando o conteúdo antigo do arquivo
    file_put_contents($file, $msg, FILE_APPEND);    
}

//função para liberar o apontamento retroativo fora do período padrão contido em _utilitaries/config.php
function global_liberarPeriodo($status) {
    printf($status);
}

//função para preparar a string para inserir no onReady da página cada permissão do usuário
function global_verificaPermissao(){
    $readyPermissoes;
    //carrega tipo de permissão
    $tipoPermissao = querySelect_listaParam();
    if(isset($tipoPermissao)){
        foreach ($tipoPermissao as $nomePermissao) {
            $tipo = $nomePermissao['TIPO'];
            if (isset($_SESSION[$tipo]) && $_SESSION[$tipo] == 1) {
                $readyPermissoes .= "js_exibeCampos('$tipo', '', false); "; //prepara string para inserir no onReady da página para cada permissão
            }      
        }
    };
    return $readyPermissoes;    
}
?>