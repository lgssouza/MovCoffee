<?php

	header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['peneira_atual']))
    {
        $peneira_atual = $_POST['peneira_atual'];
		$inf		= array();
		
		if(!empty($peneira_atual) && $peneira_atual != "undefined")		 			
		{
			$sql 	= "SELECT *
					   FROM mov_razao_produtos_peneira A
					   WHERE A.id = $peneira_atual";			
			
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
        echo json_encode(array('msg'=>'Erro Inesperado! Movimentação peneira não identificada'));
        return false;
    }	
	
?>