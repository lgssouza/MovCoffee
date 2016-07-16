<?php

	header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['lote_atual']))
    {
        $lote_atual = $_POST['lote_atual'];
		$inf		= array();
		
		if(!empty($lote_atual) && $lote_atual != "undefined")		 			
		{			
			$sql 	= "SELECT *, B.id id_mov_peneira
					   FROM mov_razao_produtos A
					   INNER JOIN mov_razao_produtos_peneira B ON B.fk_razao_produtos = A.id
					   INNER JOIN tb_peneira C ON B.fk_peneira = C.id
					   WHERE A.lote_apas = '$lote_atual' AND A.tipo_movimentacao = 'E'";
			
			$rs 	= $mysqli->query($sql);	
	
			if($rs)
			{
				$informacoes = array();
        
				while($informacao = $rs->fetch_object())
				{
					array_push($informacoes, $informacao);					
				}
				
				echo json_encode($informacoes);
				
				$rs->close();
			}			
		}
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Produtor não identificado e propriedade não identificado'));
        return false;
    }	
	
?>