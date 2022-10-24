<?php

class Config{
    public $server;
    public $connectionInfo;
    public $diaApontRetroativo;
    public $OSGenerica;
    public $nomeBaseRM;
    public $usuarioIntegracaoRM;
    public $senhaIntegracaoRM;
    public $enderecoSOAP;
    public $OSBloqueada;
    public $StatusOSAtv;
    public $legSecaoFilial;

    //construtor da classe
    function __construct(){
        $this->valorConfig();
    }

    function valorConfig(){
        $this->server = "192.168.0.10";
        $this->connectionInfo = array( "Database"=>"DSS_TEST", "UID"=>"APONTAMENTO", "PWD"=>"A@3D5A7t7a" );
        $this->nomeBaseRM = "CORPORERM_ONTEM";
        $this->diaApontRetroativo = "false";
        $this->OSGenerica = "'000000002','000000006','000000009','000000012','000000015','000000018','000000023','000000024','000000025','000000026','000000027','000000028','000000029','000000030','000000035','000000037','000000042','000000045','000000046','000000047','000000048','000000049'";
        $this->OSBloqueada = "'000000000','000000001','000000003','000000004','000000005','000000007','000000008','000000010','000000011','000000013','000000014','000000016','000000017','000000019','000000020','000000021','000000022','000000032','000000033','000000036','000000041','000000050','000000776'";
        $this->StatusOSAtv = "0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 17, 18, 19, 20";
        $this->usuarioIntegracaoRM = "APONTAMENTO";
        $this->senhaIntegracaoRM = "A@3D5A7t7A";
        $this->enderecoSOAP = "http://192.168.0.22:8051/";
        $this->legSecaoFilial =  array( "02.01.013"=>"CALD", "02.01.004"=>"MECA", "02.01.007"=>"POLO", "02.01.031"=>"ISOBOB");
    }
}
?>