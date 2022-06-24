<?php

//Arquivo para criação de instância de conexões... Para parâmentos de conexão: _utilitaries/config.php
include_once $_SERVER["DOCUMENT_ROOT"].'/_utilitaries/config.php';

//Abre conexão SQL
function connect_openSql() {
    $config = new Config();
    $conn = sqlsrv_connect($config->server, $config->connectionInfo);
    return $conn;
}

//fecha conxão SQL
function connect_closeSql($conn) {
    sqlsrv_close( $conn );
}
?>