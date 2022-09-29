<?php session_start();
	
	require "../soap/soap_write.php";
	require "../soap/soap_write_coligada02.php";
	require "../sql/conectaDss.php";

	
if(isset($_POST)){
	
			
	$chapa = $_SESSION['chapa'];
	$os = $_SESSION['nos'];
	$hini = $_SESSION['hini'];
	$hfim = $_SESSION['hfim'];
	$data = $_SESSION['data'];
	$retrabalho = $_SESSION['retrabalho'];
	$causaRetrabalho = $_SESSION['causaRetrabalho'];
	$servCampo = $_SESSION['servCampo'];


	if($servCampo == 'S'){
		$sCampo = $servCampo;
	}else {
		$sCampo = 'N';
	}
		
		
	if($retrabalho == 'N'){
		$retrab = 'NA';
	}else{
		$retrab = $causaRetrabalho;
	}
	

	if(strlen($os) == 5 ){
				$numOs = '0000' . $os;
				}elseif(strlen($os) == 6){
				$numOs= '000'. $os;
				}elseif($os <= '000033'){
				$numOs= '000'. $os;
				
			}
	
	
		$dados_sql = "select * from VIEW_MODAL_OS where TMOV_NUMEROMOV = '{$numOs}'";
        $resultado = sqlsrv_query($conn, $dados_sql);
        $result = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
		$dataDig = date('d/m/Y');
		
		
		
	$coligada = $result['TMOV_COLIGADA'];
	
	
	 $idprd = "";
        if($coligada == '1'){
            $idprd = '4109';
        }else{
            $idprd = '41831';
            
        }

		if(strlen($os) == 5 ){
				$numeOs = '0' . $os;
				}else{
					$numeOs = $os;
				}

		
		
			$xml= "
			<MovMovimento >
				<TMOV>
					<CODCOLIGADA>{$coligada}</CODCOLIGADA>
					<IDMOV>-1</IDMOV>
					<CODFILIAL>1</CODFILIAL>
					<CODLOC>01</CODLOC>
					<CODLOCDESTINO>01</CODLOCDESTINO>
					<NUMEROMOV>-1</NUMEROMOV>
					<SERIE>----</SERIE>
					<CODTMV>1.2.04</CODTMV>
					<TIPO>A</TIPO>
					<STATUS>N</STATUS>
					<DATAEMISSAO>{$data}</DATAEMISSAO> 
					<DATAEXTRA1>{$dataDig}</DATAEXTRA1> 					
					<DATAMOVIMENTO></DATAMOVIMENTO>
					<HORULTIMAALTERACAO></HORULTIMAALTERACAO>    
					<DATACRIACAO></DATACRIACAO>     
					<CODCOLIGADA1>1</CODCOLIGADA1>
					<IDMOVHST>-1</IDMOVHST>
				</TMOV>   
				<TITMMOV>
					<CODCOLIGADA>{$coligada}</CODCOLIGADA>
					<IDMOV>-1</IDMOV>
					<NSEQITMMOV>1</NSEQITMMOV>
					<CODFILIAL>1</CODFILIAL>
					<NUMEROSEQUENCIAL>1</NUMEROSEQUENCIAL>
					<IDPRD>{$idprd}</IDPRD>
					<CODIGOPRD>99.02.0001</CODIGOPRD>
					<NOMEFANTASIA>HORAS TRABALHADAS</NOMEFANTASIA>
					<CODIGOREDUZIDO>05133</CODIGOREDUZIDO>
					<NUMNOFABRIC>99.02.0001</NUMNOFABRIC>
					<QUANTIDADE>1</QUANTIDADE>
					<DATAEMISSAO></DATAEMISSAO>
					<CAMPOLIVRE>{$numeOs}</CAMPOLIVRE>
					<CODUND>H</CODUND>
					<QUANTIDADEARECEBER>1</QUANTIDADEARECEBER>
					<QUANTIDADEORIGINAL>1</QUANTIDADEORIGINAL>
					<FLAG>0</FLAG>
					<BLOCK>0</BLOCK>
					<QTDEVOLUMEUNITARIO>1</QTDEVOLUMEUNITARIO>
					<CODLOC>01</CODLOC>    
					<PRECOUNITARIOSELEC>256</PRECOUNITARIOSELEC>
					<QUANTIDADETOTAL>1</QUANTIDADETOTAL>
					<PRODUTOSUBSTITUTO>0</PRODUTOSUBSTITUTO>
					<INTEGRAAPLICACAO>T</INTEGRAAPLICACAO>
					<CODCOLIGADA1>1</CODCOLIGADA1>
					<IDMOVHST>-1</IDMOVHST>
					<NSEQITMMOV1>1</NSEQITMMOV1>
				</TITMMOV>  
				<TITMMOVCOMPL>
					<CODCOLIGADA>{$coligada}</CODCOLIGADA>
					<IDMOV>-1</IDMOV>
					<NSEQITMMOV>1</NSEQITMMOV>
					<REQUISITANTE>{$chapa}</REQUISITANTE>
					<HORAINICIO>{$hini}</HORAINICIO>
					<HORAFINAL>{$hfim}</HORAFINAL>
					<DESCCOMPL>APP DO TI</DESCCOMPL>
					<TIPORETRABALHO>{$retrab}</TIPORETRABALHO>
					<SERV_CAMPO>{$sCampo}</SERV_CAMPO>
					<RECMODIFIEDBY></RECMODIFIEDBY>
					<RECMODIFIEDON></RECMODIFIEDON>
					<NOS>{$numeOs}</NOS>    
				</TITMMOVCOMPL>  
				<TMOVTRANSP>
					<CODCOLIGADA>{$coligada}</CODCOLIGADA>
					<IDMOV>-1</IDMOV>
					<LOTACAO>1</LOTACAO>
				</TMOVTRANSP>
				</MovMovimento>
			";

		if($coligada == '1'){
                $funcao=	f_soap_write('MovMovimentoTBCData',$xml);
                $resultado_xml = $_SESSION['result_xml'];
				
			
            }else{
                $funcao=	f_soap_write_coligada02('MovMovimentoTBCData',$xml);
                $resultado_xml = $_SESSION['result_xml'];

            }
			
		if(isset($resultado_xml)){
			
			
			
			if(strlen($os) == 5 ){
				$numOs = '0000' . $os;
				}elseif(strlen($os) == 6){
				$numOs= '000'. $os;
				}elseif($os <= '000034'){
				$numOs= '000'. $os;
				
			}
			
		
			$data = str_replace("/", "-", $_SESSION['data']);
			$dataReal= date('Y-m-d', strtotime($data));
			

			$sql = "INSERT INTO LOG_APONTAMENTO (N_OS, ID_USUARIO, VALIDA, H_INICIO, H_FIM, H_LANCAMENTO,ORIGEM, RETRABALHO, SERV_CAMPO ) VALUES ('$numOs', '$chapa', 'A','$dataReal ' + '$hini' ,'$dataReal ' + '$hfim' , GETDATE(),'APP DO TI', '$retrab', '$sCampo')";

			
			$stmt = sqlsrv_query( $conn, $sql);                                                                    
			$result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
							
			$_SESSION['msg']='sucesso';
				
			header("Location: ../files/confirmaApontamento.php?msg=sucesso");
					
					
		}
		
		
}

	