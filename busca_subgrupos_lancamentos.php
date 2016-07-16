<?php

	header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['idgrupo']))
    {
        $idgrupo = $_POST['idgrupo'];
		$subgruposarray = array();
		
		if(!empty($idgrupo) && $idgrupo != "undefined")		 			
		{
			$sql 	= "SELECT * FROM tb_pdcj_subgrupo_rel WHERE fk_id_grupo = " . $idgrupo;			
			
			$rs 	= $mysqli->query($sql);	
	
			if($rs)
			{
				$subgrupos = array();
        
				while($subgrupo = $rs->fetch_object())
				{
					array_push($subgrupos, $subgrupo);
					
				}
				
				for($i=0;$i<count($subgrupos);$i++)
				{
						$subgruposarray[] = array(
						'id_subgrupo'	 => $subgrupos[$i]->id_subgrupo,
						'subg_descricao' => $subgrupos[$i]->subg_descricao,
						);
				}
				echo json_encode($subgruposarray);
				
				$rs->close();
			}			
		}
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Grupo não identificado'));
        return false;
    }
	
	
	
	
?>