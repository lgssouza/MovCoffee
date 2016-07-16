<?php 
 
    header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['acao']))
    {
        $acao = $_POST['acao'];
		
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
        return false;
    }
    		 
    if(!strcmp($acao,'adicionar') || !strcmp($acao,'editar'))
    {    	
		
		$idgrupo 	= $mysqli->real_escape_string($_POST['idgrupo']);		
		$objetivo 	= $mysqli->real_escape_string($_POST['objetivo']);
		$ano 		= $mysqli->real_escape_string($_POST['ano']);
				 
        if(!strcmp($acao,'adicionar'))
        {
        
			$rsverifica = $mysqli->query("select count(*) as total 
										  from tb_pdcj_grupo_plan_valores a 
										  where a.fk_id_grupo = $idgrupo and a.grup_ano = '$ano'");
	
			if($rsverifica)
			{
				$contador = array();
		        
				while($tudosp = $rsverifica->fetch_object())
				{
					array_push($contador, $tudosp);
				}
				
				$rsverifica->close();
				
				for($i=0;$i<count($contador);$i++)
				{
					$cont = $contador[$i]->total;				
				}										          	
			}			
			 	
			if ($cont==0)
			{
				
				if(!$rs = $mysqli->query("INSERT INTO `tb_pdcj_grupo_plan_valores` 
	            						(            						 
	            						 `fk_id_grupo`, 
	            						 `grup_objetivo`, 
	            						 `grup_orcado_premio`, 
	            						 `grup_orcado_outros`, 
	            						 `grup_orcado_total`, 
	            						 `grup_perc_premio`, 
	            						 `grup_perc_outros`, 
	            						 `grup_perc_total`, 
	            						 `grup_ano`
	            						)
	            						VALUES
	            						(
	            						
	            						 $idgrupo, 
	        							'$objetivo',        
	        							 0,
	        							 0,					 
	            						 0,
	            						 0,
	            						 0,
	            						 0,	            						  
	            						'$ano'
										)"))
										
	            {	
	            	echo json_encode(array('msg'=>'Error ao gravar Orçamento'));
	                return false;
	            }
				
	            
	            $idgrupovalores 	= $mysqli->insert_id;
				
	            echo json_encode(array('msg' => 'sucesso')); 
            
			}
			else
			{
				echo json_encode(array('msg' => 'validacao')); 
			}
       	}
        elseif(!strcmp($acao,'editar'))
        {
            if(isset($_POST['idgrupovalores']))
            {
                $idgrupovalores	= $_POST['idgrupovalores'];
                
            }
            else
            {
                echo json_encode(array('msg'=>'grupo não identificado para alteração'));
                return false;
            }
 			
			$rsverifica2 = $mysqli->query("select count(*) as total 
										  from tb_pdcj_grupo_plan_valores a 
										  where a.id_grupo_valores != $idgrupovalores and a.fk_id_grupo = $idgrupo and a.grup_ano = '$ano'");
										  
			$cont2 =0;
			if($rsverifica2)
			{
				$contador2	= $rsverifica2->fetch_object();
				$cont2 		= $contador2->total;				
				$rsverifica2->close();
														          	
			}

 			if ($cont2==0)
			{	
	            if(!$rs = $mysqli->query("UPDATE tb_pdcj_grupo_plan_valores 
	                                  		SET 
	                                  		fk_id_grupo					= $idgrupo,                                  		
	            							grup_objetivo				= '$objetivo', 
	            							grup_ano					= '$ano'
	                                      	WHERE id_grupo_valores 		=  $idgrupovalores"))
	            {
	            	
	                return false;
	            }
				echo json_encode(array ('msg' => 'sucesso'));
			}
			else
			{
				echo json_encode(array('msg' => 'validacao'));
			}
        }
    }
    elseif(!strcmp($acao,'excluir'))
    {    	
        if(isset($_POST['idgrupovalores']))
        {
            $id = $_POST['idgrupovalores'];
        }
        else
        {
            echo json_encode(array('msg'=>'orçamento não identificado para exclusão'));
            return false;
        }
        		         
        if(!$res = $mysqli->query("DELETE FROM tb_pdcj_grupo_plan_valores WHERE id_grupo_valores = " . $id))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do orçamento'));
            return false;
        }
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>