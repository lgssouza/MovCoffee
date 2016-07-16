<?php

	header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['idprodutor']))
    {
        $idprodutor 		= $_POST['idprodutor'];
		$propriedadessarray = array();
		
		if(!empty($idprodutor) && $idprodutor != "undefined")		 			
		{
			$sql 	= "SELECT b.id_prop, b.prop_nome
					   FROM mov_prop_prod A
					   INNER JOIN tb_propriedade B ON A.fk_id_prop = B.id_prop
					   WHERE A.fk_id_prod = " . $idprodutor;			
			
			$rs 	= $mysqli->query($sql);	
	
			if($rs)
			{
				$propriedades = array();
        
				while($propriedade = $rs->fetch_object())
				{
					array_push($propriedades, $propriedade);
					
				}
				
				for($i=0;$i<count($propriedades);$i++)
				{
						$propriedadessarray[] = array(
							'id_prop'	=> $propriedades[$i]->id_prop,
							'prop_nome' => $propriedades[$i]->prop_nome
						);
				}
				
				echo json_encode($propriedadessarray);
				
				$rs->close();
			}			
		}
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Produtor não identificado'));
        return false;
    }	
	
?>