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
    	
        $idprodutor 	= $mysqli->real_escape_string($_POST['idprodutor']);
		$idpropriedade 	= $mysqli->real_escape_string($_POST['idpropriedade']);
		$talhao 		= $mysqli->real_escape_string($_POST['talhao']);
		$variedade 		= $mysqli->real_escape_string($_POST['variedade']);
		$esp1 			= $mysqli->real_escape_string($_POST['esp1']);		
		$esp1 			= str_replace(',','.',$esp1);				
		$esp2 			= $mysqli->real_escape_string($_POST['esp2']);
		$esp2 			= str_replace(',','.',$esp2);			
		$area 			= $mysqli->real_escape_string($_POST['area']);
		$area 			= str_replace(',','.',$area);
		$qtdplantas 	= $mysqli->real_escape_string($_POST['qtdplantas']);
		$anoplantio 	= $mysqli->real_escape_string($_POST['anoplantio']);
		$safra 			= $mysqli->real_escape_string($_POST['safra']);
		$previsao 		= $mysqli->real_escape_string($_POST['previsao']);
		$producao 		= $mysqli->real_escape_string($_POST['producao']);
		
		if(empty($qtdplantas)){
			$qtdplantas = 0;
		}

		if(empty($previsao)){
			$previsao = 0;
		}
						
		if(empty($producao)){
			$producao = 0;
		}
		
		 
        if(!strcmp($acao,'adicionar'))
        {           
            if(!$rs = $mysqli->query("INSERT INTO tb_safra (
                                                            	fk_id_produtor,
                                                            	fk_id_prop,
                                                            	safra_talhao,
                                                            	safra_variedade,
                                                            	safra_esp1,
                                                            	safra_esp2,
                                                            	safra_area,
                                                            	safra_anoplantio,
                                                            	safra_numeroplantas,
                                                            	safra_safra,
                                                            	safra_previsao,
                                                            	safra_producao
                                                           	  ) 
                                                     	values(
                                                    			 $idprodutor,
                                                    			 $idpropriedade,
                                                             	'$talhao',
                                                              	'$variedade',
                                                              	 $esp1,
                                                              	 $esp2,
                                                              	 $area,
                                                              	'$anoplantio',
                                                              	 $qtdplantas,
                                                              	'$safra',
                                                              	 $previsao,
                                                              	 $producao                                                               
                                                           	  )"))
            {
            	echo json_encode(array('msg'=>'Error ao gravar banco'));
                return false;
            }
             
            $idsafra = $mysqli->insert_id;
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acao,'editar'))
        {
            if(isset($_POST['id']))
            {
                $idsafra 		= $_POST['id'];
                 
            }
            else
            {
                echo json_encode(array('msg'=>'Safra não identificada para alteração'));
                return false;
            }
 
            if(!$rs = $mysqli->query("UPDATE tb_safra 
                                  		SET fk_id_produtor 	= $idprodutor,
                                  		fk_id_prop		 	= $idpropriedade,
                                      	safra_talhao   		= '$talhao',
                                      	safra_variedade		= '$variedade',
                                      	safra_esp1 			= $esp1,
                                      	safra_esp2 			= $esp2,
                                      	safra_area 			= $area,
                                      	safra_anoplantio 	= '$anoplantio',
                                      	safra_numeroplantas = $qtdplantas,                                      	
                                      	safra_safra 		= '$safra',
                                      	safra_previsao 		= $previsao,
                                      	safra_producao 		= $producao
                                      	WHERE id_safra 		=  $idsafra"))
            {
            	echo "UPDATE tb_safra 
                                  		SET fk_id_produtor 	= $idprodutor,
                                  		fk_id_prop		 	= $idpropriedade,
                                      	safra_talhao   		= '$talhao',
                                      	safra_variedade		= '$variedade',
                                      	safra_esp1 			= $esp1,
                                      	safra_esp2 			= $esp2,
                                      	safra_area 			= $area,
                                      	safra_anoplantio 	= '$anoplantio',
                                      	safra_numeroplantas = $qtdplantas,                                      	
                                      	safra_safra 		= '$safra',
                                      	safra_previsao 		= $previsao,
                                      	safra_producao 		= $producao
                                      	WHERE id_safra 		=  $idsafra";
                echo json_encode(array('msg'=>'Erro na alteração da safra'));
                return false;
            }
			
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acao,'excluir'))
    {
    	
        if(isset($_POST['id']))
        {
            $idsafra = $_POST['id'];
            
        }
        else
        {
            echo json_encode(array('msg'=>'Safra não identificada para exclusão'));
            return false;
        }
		
		         
        if(!$res = $mysqli->query("DELETE FROM tb_safra WHERE id_safra = " . $idsafra))
        {
            echo json_encode(array('msg'=>'Erro na exclusão da safra'));
            return false;
        }
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>