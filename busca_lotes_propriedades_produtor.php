<?php

	header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['idprodutor']) && isset($_POST['idpropriedade']))
    {
    	$loteatual					= $_POST['loteatual'];
        $idprodutor 				= $_POST['idprodutor'];
		$idpropriedade 				= $_POST['idpropriedade'];
		$lotespropriedadessarray	= array();
				  
		if(!empty($idprodutor) && $idprodutor != "undefined")		 			
		{
			$sql 	= "SELECT b.id, b.lote_apas, b.lote_coperativa
					   FROM mov_prop_prod A
					   INNER JOIN mov_razao_produtos B ON A.id_mov_pp = B.fk_mov_pp
					   WHERE A.fk_id_prod = $idprodutor  
					   AND A.fk_id_prop =  $idpropriedade 
					   AND B.tipo_movimentacao = 'E'
					   AND 
					   (B.lote_apas = '$loteatual' OR B.lote_apas NOT IN 
						   (
							   	SELECT lote_apas 
							    FROM mov_razao_produtos
							    WHERE tipo_movimentacao = 'S'
						   )
					    )";		   			
			
			$rs 	= $mysqli->query($sql);	
	
			if($rs)
			{
				$lotes = array();
        
				while($lote = $rs->fetch_object())
				{
					array_push($lotes, $lote);
					
				}
				
				for($i=0;$i<count($lotes);$i++)
				{
						$lotespropriedadessarray[] = array(
							'id'				=> $lotes[$i]->id,
							'lotes_apas'		=> $lotes[$i]->lote_apas,
							'lotes_coperativa' 	=> $lotes[$i]->lote_coperativa
						);
				}
				
				echo json_encode($lotespropriedadessarray);
				
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