<?php
	
	echo ()
	
	require '../sql/sql.php';
	
	if (!empty($_POST) AND (empty($_POST['usuario']) OR empty($_POST['senha']))) {
      header("Location: index.php"); exit;
  }
   $usuario = mysql_real_escape_string($_POST['usuario']);
   $senha = mysql_real_escape_string($_POST['senha']);
  
  $myquery1 = "
	select
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

	INNER JOIN PFUNC (NOLOCK) ON
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
		AND PSECAO.CODFILIAL = GFILIAL.CODFILIAL
		where (`usuario` = '".$usuario ."') and
		(`senha` = '".$senha ."') 
		
		LIMIT 1
	
		";
		f_sql_query($myquery1); 

  if (mysql_num_rows($myquery1) != 1) {
      // Mensagem de erro quando os dados são inválidos e/ou o usuário não foi encontrado
      echo "Login inválido!"; exit;
  } else {
      // Salva os dados encontados na variável $resultado
      $resultado = mysql_fetch_assoc($myquery1);
  }
  


      // Se a sessão não existir, inicia uma
      if (!isset($_SESSION)) session_start();

      // Salva os dados encontrados na sessão
      $_SESSION['UsuarioID'] = $resultado['LOGIN'];
      $_SESSION['UsuarioNome'] = $resultado['PPESSOA_NOME'];


      // Redireciona o visitante
      header("Location: restrito.php"); exit;
  }
	
?>