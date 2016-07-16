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
    		
		$idsubgrupo = $mysqli->real_escape_string($_POST['idsubgrupo']);
		$idgrupo 	= $mysqli->real_escape_string($_POST['idgrupo']);
		$premio 	= $mysqli->real_escape_string($_POST['premio']);
		$outros 	= $mysqli->real_escape_string($_POST['outros']);
		$avaliacao 	= $mysqli->real_escape_string($_POST['avaliacao']);
		$ano 		= $mysqli->real_escape_string($_POST['ano']);
		$premio 	= str_replace(',','.',$premio);	
		$outros		= str_replace(',','.',$outros);
		$total 		= $premio + $outros;	
		
    	 
        if(!strcmp($acao,'adicionar'))
        {
    		$rsverifica = $mysqli->query("select count(*) as total 
										  from tb_pdcj_subgrupo_rel_valores a 
										  where a.fk_id_subg = $idsubgrupo and a.subg_ano = '$ano'");
	
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
				if(!$rs = $mysqli->query("INSERT INTO `tb_pdcj_subgrupo_rel_valores` 
	            						(            						 
	            						`fk_id_subg`, 
	            						`subg_premio_orcado`,            						
	            						`subg_premio_despesas`,
	            						`subg_premio_saldo`,
	            						`subg_outros_orcado`,            						 
	            						`subg_outros_despesas`,
	            						`subg_outros_saldo`,
	            						`subg_total_orcado`,
	            						`subg_total_despesas`,
	            						`subg_total_saldo`,
	            						`subg_avaliacao`, 
	            						`subg_ano`
	            						)
	            						VALUES
	            						(	            						
	            						 $idsubgrupo, 
	        							 $premio,        
	        							 0,
	        							 $premio,					 
	            						 $outros,
	            						 0,
	            						 $outros,
	            						 $total, 
	            						 0,
	            						 $total,           						 
	            						'$avaliacao', 
	            						'$ano'
										)"))
										
	            {
	            	echo json_encode(array('msg'=>'Error ao gravar Orçamento'));
	                return false;
	            }
				
	            
	            $idsubgrupovalores 	= $mysqli->insert_id;
				
	            echo json_encode(array('msg' => 'sucesso')); 
            
			}
			else
			{
				echo json_encode(array('msg' => 'validacao')); 
			}
       	}
        elseif(!strcmp($acao,'editar'))
        {
            if(isset($_POST['idsubgrupovalores']))
            {
                $idsubgrupovalores	= $_POST['idsubgrupovalores'];
            }
            else
            {
                echo json_encode(array('msg'=>'grupo não identificado para alteração'));
                return false;
            }
			
			$rsverifica2 = $mysqli->query("select count(*) as total 
										  from tb_pdcj_subgrupo_rel_valores a 
										  where a.id_subgrupo_valores != $idsubgrupovalores and a.fk_id_subg = $idsubgrupo and a.subg_ano = '$ano'");
	
			if($rsverifica2)
			{
				$contador2 = array();
		        
				while($tudosp2 = $rsverifica2->fetch_object())
				{
					array_push($contador2, $tudosp2);
				}
				
				$rsverifica2->close();
				
				for($i=0;$i<count($contador2);$i++)
				{
					$cont2 = $contador2[$i]->total;				
				}										          	
			}
			
			if ($cont2==0)
			{	 
	            if(!$rs = $mysqli->query("UPDATE tb_pdcj_subgrupo_rel_valores 
	                                  		SET 
	                                  		fk_id_subg 					=  $idsubgrupo,
	                                  		subg_premio_orcado 			=  $premio ,            						
	            							subg_outros_orcado			=  $outros,            						 
	            							subg_total_orcado			=  $total,
	            							subg_avaliacao				= '$avaliacao', 
	            							subg_ano					= '$ano'
	                                      	WHERE id_subgrupo_valores 	=  $idsubgrupovalores"))
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
        if(isset($_POST['idsubgrupovalores']))
        {
            $id = $_POST['idsubgrupovalores'];
        }
        else
        {
            echo json_encode(array('msg'=>'orçamento não identificado para exclusão'));
            return false;
        }
        
        //buscar grupo e ano para sp.
        $sqlsp 	= "SELECT a.subg_ano, b.fk_id_grupo FROM tb_pdcj_subgrupo_rel_valores a 
        inner join tb_pdcj_subgrupo_rel b on a.fk_id_subg = b.id_subgrupo 
        where a.id_subgrupo_valores = " . $id;
	
		$rssp     = $mysqli->query($sqlsp);
	
		if($rssp)
		{
			$grupo = array();
	        
			while($tudosp = $rssp->fetch_object())
			{
				array_push($grupo, $tudosp);
			}
			
			$rssp->close();
			
			for($i=0;$i<count($grupo);$i++)
			{
				$grupoid = $grupo[$i]->fk_id_grupo;
				$grupoano = $grupo[$i]->subg_ano;
			}										          	
		}
				         
        if(!$res = $mysqli->query("DELETE FROM tb_pdcj_subgrupo_rel_valores WHERE id_subgrupo_valores = " . $id))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do orçamento'));
            return false;
        }
        $sp = $mysqli->query("call SP_AtualizaPDCJGrupoValores($grupoano,$grupoid)"); 
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>