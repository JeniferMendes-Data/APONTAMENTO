<?php session_start();

require "../soap/soap_write.php";
require "../soap/soap_write_coligada02.php";
require "../sql/conectaDss.php";

$status = $_POST['status'];


foreach($status as $data){
    $data_os = explode('-', $data);

    if($data_os[0] == 'A'){

        $dados_sql = "SELECT	* 	FROM	VIEW_LANCAMENTOS_HIST_OS WHERE ID= $data_os[1]";
        $resultado = sqlsrv_query($conn, $dados_sql);
        $result = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
		

        $h_inicio = $result['H_INICIO'];
        $hini= $h_inicio->format('H:i');

        $data = $result['H_INICIO'];
        $datainicio= $data->format('d-m-Y');

        $origem = $result['ORIGEM'];

        $coligada = $result['COLIGADA_COD'];
		$retrabalho = $result['RETRABALHO'];
		$servCampo = $result['SERV_CAMPO'];

        $h_fim = $result['H_FIM'];
        $hfim= $h_fim->format('H:i');
        $requisitante= $result['CHAPA'];
        $req = trim($requisitante);

        $nos= $result['OS'];
        $n_nos = trim($nos);

        if(strlen($n_nos) ==9 ){
            $numOs= substr($n_nos,3);
        }else {
            $numOs = $n_nos;
        }

        if($origem <>'APP DO TI'){ /*alterar aqui*/
            $complementar = 'INSERCAO PELO APP DA SALA DE TESTES';
        }else{
            $complementar = 'INSERCAO PELO APP DO TI';
        }
	
        $idprd = "";
        if($coligada == '1'){
            $idprd = '4109';

        }elseif($coligada == '2'){
            $idprd = '41831';
        }else{
			$coligada = '1';
			$idprd = '4109';
			

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
					<DATAEMISSAO>{$datainicio}</DATAEMISSAO>       
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
					<CAMPOLIVRE>{$numOs}</CAMPOLIVRE>
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
					<REQUISITANTE>{$req}</REQUISITANTE>
					<HORAINICIO>{$hini}</HORAINICIO>
					<HORAFINAL>{$hfim}</HORAFINAL>
					<DESCCOMPL>{$complementar}</DESCCOMPL>
					<TIPORETRABALHO>{$retrabalho}</TIPORETRABALHO>
					<SERV_CAMPO>{$servCampo}</SERV_CAMPO>
					<RECMODIFIEDBY></RECMODIFIEDBY>
					<RECMODIFIEDON></RECMODIFIEDON>
					<NOS>{$numOs}</NOS>    
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

            $query = "UPDATE VIEW_LANCAMENTOS_HIST_OS SET VALIDA= 'A' WHERE ID= $data_os[1]";

            $update = sqlsrv_query($conn, $query);

            $_SESSION['msg']= 'sucesso';


            header("Location: ../files/gerencia.php?msg=sucesso");

        }


    }else if($data_os[0] == 'R'){
        if(!(isset($resultado_xml))){

            $query_reprov = "UPDATE VIEW_LANCAMENTOS_HIST_OS SET VALIDA= 'R' WHERE ID= $data_os[1]";

            $update = sqlsrv_query($conn, $query_reprov);

            $_SESSION['msg']= 'sucesso';

            header("Location: ../files/gerencia.php?msg=sucesso");
        }
    }


}
if(empty($status)){
    $_SESSION['ms2']='sucesso';
    header("Location: ../files/gerencia.php?msg2=sucesso");

}
	