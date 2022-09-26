<?php
$login = $_POST['usuario'];
$entrar = $_POST['acessar'];
$senha = $_POST['senha'];
//$connect = sql.php


  if (isset($entrar)) {

    $verifica = "select
	PPESSOA.NOME												'PPESSOA_NOME',
	PPESSOA.CODIGO												'PPESSOA_CODIGO',
	PPESSOA.CPF													'PPESSOA_CPF',
	
	ISNULL(PFUNC.CHAPA,PPESSOA.CODIGO)							'LOGIN', 
	VPCOMPL.CODPESSOA											'VPCOMPL_PWDDSS',

	ISNULL(GFILIAL.CODFILIAL,'0')								'GFILIAL_CODFILIAL',
	ISNULL(GFILIAL.NOME,'TERCEIRO')								'GFILIAL_NOME',

	ISNULL(GDEPTO.CODDEPARTAMENTO,'00')							'GDEPTO_CODDEPARTAMENTO',
	ISNULL(GDEPTO.NOME,'TERCEIRO')								'GDEPTO_NOME',
	
	ISNULL(PSECAO.CODDEPTO,'00.00.000')							'PSECAO_CODDEPTO',
	ISNULL(PSECAO.DESCRICAO,'TERCEIRO')							'PSECAO_DESCRICAO'

	from PPESSOA

	LEFT JOIN PFUNC (NOLOCK) ON
		PPESSOA.CODIGO = PFUNC.CODPESSOA
		AND PFUNC.CODSITUACAO <> 'D'

	LEFT JOIN PSECAO (NOLOCK) ON
		PFUNC.CODCOLIGADA = PSECAO.CODCOLIGADA
		AND PFUNC.CODSECAO = PSECAO.CODIGO

	LEFT JOIN VPCOMPL (NOLOCK) ON
		PPESSOA.CODIGO = VPCOMPL.CODPESSOA

	LEFT JOIN GDEPTO (NOLOCK) ON
		PSECAO.CODCOLIGADA = GDEPTO.CODCOLIGADA
		AND PSECAO.CODFILIAL = GDEPTO.CODFILIAL
		AND PSECAO.CODDEPTO = GDEPTO.CODDEPARTAMENTO

	LEFT JOIN GFILIAL (NOLOCK) ON
		PSECAO.CODCOLIGADA = GFILIAL.CODCOLIGADA
		AND PSECAO.CODFILIAL = GFILIAL.CODFILIAL WHERE LOGIN =
    '$login' AND VPCOMPL_PWDDSS = '$senha'";
	
	
      if (mysql_num_rows($verifica)<=0){
        echo"<script language='javascript' type='text/javascript'>
        alert('Login e/ou senha incorretos');window.location
        .href='login.html';</script>";
        die();
      }else{
        setcookie("login",$login);
        header("Location:apontamento.php");
      }
  }
  
    
  
?>