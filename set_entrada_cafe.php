<?php 
 
    header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['acaoentradacafe']))
    {
        $acaoentradacafe = $_POST['acaoentradacafe'];
		
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
        return false;
    }
    		 
    if(!strcmp($acaoentradacafe,'adicionar') || !strcmp($acaoentradacafe,'editar'))
    {
    	$identcafe 					= $mysqli->real_escape_string($_POST['identcafe']);
        $produtor 					= $mysqli->real_escape_string($_POST['produtor']);
		$propriedade 				= $mysqli->real_escape_string($_POST['propriedade']);
		$dataentcafe 				= implode("-",array_reverse(explode("/",($_POST['dataentcafe']))));	
		$loteapasentcafe 			= $mysqli->real_escape_string($_POST['loteapasentcafe']);
		$lotecoperativaentcafe 		= $mysqli->real_escape_string($_POST['lotecoperativaentcafe']);
		$quantidadeentcafe 			= str_replace(',','.',$mysqli->real_escape_string($_POST['quantidadeentcafe']));
		$umidadeentcafe 			= str_replace(',','.',$mysqli->real_escape_string($_POST['umidadeentcafe']));
        $bebidaentcafe 				= str_replace(',','.',$mysqli->real_escape_string($_POST['bebidaentcafe']));
		$obsevacaoentcafe 			= $mysqli->real_escape_string($_POST['obsevacaoentcafe']);
		$produto 					= $mysqli->real_escape_string($_POST['produto']);
		$tipomovimentacao 			= $mysqli->real_escape_string($_POST['tipomovimentacao']);
		$objlistainformacoespeneira	= $_POST['listainformacoespeneira'];
		
		if(empty($umidadeentcafe))
		{
			$umidadeentcafe = 'NULL';
		} 
		
		if(empty($bebidaentcafe))
		{
			$bebidaentcafe = 'NULL';
		}
		 
		$sql = "SELECT id_mov_pp FROM mov_prop_prod
				WHERE fk_id_prop = $propriedade 
				AND fk_id_prod = $produtor";
				
		$rs 		= $mysqli->query($sql);
		$fk_mov_pp 	= $rs->fetch_object();
		
		if(!strcmp($acaoentradacafe,'adicionar'))
		{
			$rsverifica1 = $mysqli->query("SELECT count(*) as total FROM mov_razao_produtos WHERE lote_apas = $loteapasentcafe");
			$rsverifica2 = $mysqli->query("SELECT count(*) as total FROM mov_razao_produtos WHERE lote_coperativa = $lotecoperativaentcafe");
		}
		else		
		{
			$rsverifica1 = $mysqli->query("SELECT count(*) as total FROM mov_razao_produtos WHERE lote_apas = $loteapasentcafe and id != $identcafe");
			$rsverifica2 = $mysqli->query("SELECT count(*) as total FROM mov_razao_produtos WHERE lote_coperativa = $lotecoperativaentcafe and id != $identcafe");
		}
		
		if($rsverifica1)
		{
			$contador1 = array();
	        
			while($qtd1 = $rsverifica1->fetch_object())
			{
				array_push($contador1, $qtd1);
			}
			
			$rsverifica1->close();
			
			for($i=0;$i<count($contador1);$i++)
			{
				$cont1 = $contador1[$i]->total;	
				if($cont1==1)
				{
					echo json_encode(array('msg' => 'validacaoloteapas'));
					return false;
					
				}			
			}										          	
		}
			
		if($rsverifica2)
		{
			$contador2 = array();
	        
			while($qtd2 = $rsverifica2->fetch_object())
			{
				array_push($contador2, $qtd2);
			}
			
			$rsverifica2->close();
			
			for($i=0;$i<count($contador2);$i++)
			{
				$cont2 = $contador2[$i]->total;	
				if($cont2==1)
				{					
					echo json_encode(array('msg' => 'validacaolotecoperativa'));
					return false;								
				}			
			}										          	
		}
					
       	if(!strcmp($acaoentradacafe,'adicionar'))
        {      
            if(!$rs = $mysqli->query("INSERT INTO mov_razao_produtos (
		                                                            	fk_produto,
		                                                            	fk_mov_pp,
		                                                            	data,
		                                                            	lote_apas,
		                                                            	lote_coperativa,
		                                                            	tipo_movimentacao,
		                                                            	umidade,
		                                                            	bebida,
		                                                            	observacao,
		                                                            	quantidade
		                                                           	  ) 
		                                                     	values(
		                                                        		 $produto,
		                                                             	 $fk_mov_pp->id_mov_pp,
		                                                              	'$dataentcafe',
		                                                              	'$loteapasentcafe',
		                                                              	'$lotecoperativaentcafe',
		                                                          		'$tipomovimentacao',
		                                                              	 $umidadeentcafe,
		                                                              	 $bebidaentcafe,
		                                                                '$obsevacaoentcafe',
		                                                              	 $quantidadeentcafe                                                              
		                                                           	  )"))
		            {	
                echo json_encode(array('msg'=>'Error ao gravar entrada de café'));
                return false;
            }
             
            $identcafe = $mysqli->insert_id;
            
            if(!is_null($objlistainformacoespeneira))
            {
                $tamanho  = count($objlistainformacoespeneira) - 1;
                 
                for($i = 0 ; $i <= $tamanho; $i++)
                {
                    $idpeneiraentcafe       	= $objlistainformacoespeneira[$i]['idpeneiraentcafe'];
                    $percentualpeneiraentcafe   = str_replace(',','.',$objlistainformacoespeneira[$i]['percentualpeneiraentcafe']);
                    $sacaspeneiraentcafe      	= str_replace(',','.',$objlistainformacoespeneira[$i]['sacaspeneiraentcafe']);
                       
                    if(empty($percentualpeneiraentcafe))
                    {   
                       $percentualpeneiraentcafe = 0;
                    } 
                    
					if(empty($sacaspeneiraentcafe))
                    {   
                       $sacaspeneiraentcafe = 0;
                    }
					             
                    if(!$rs = $mysqli->query("INSERT INTO mov_razao_produtos_peneira(
			                                                                         fk_razao_produtos,
			                                                                         fk_peneira,
			                                                                         percentual_sacas,
			                                                                         sacas
			                                                                        ) 
		                                                                      values(
		                                                                             $identcafe,
		                                                                             $idpeneiraentcafe,
		                                                                             $percentualpeneiraentcafe,
		                                                                             $sacaspeneiraentcafe
		                                                                            )"))
                    {
                        echo json_encode(array('msg'=>'Error ao gravar peneiras da entrada de café'));
                        return false;
                    }
                     
                    $identcafepeneira = $mysqli->insert_id;
                }
            }
            
            $rs = $mysqli->query("call SP_AtualizaSaldoProdutorPropriedade($produto,$fk_mov_pp->id_mov_pp)");
                        
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acaoentradacafe,'editar'))
        {
            if(isset($_POST['identcafe']))
            {
                $identcafe = $_POST['identcafe'];                 
            }
            else
            {
                echo json_encode(array('msg'=>'Entrada de café não identificada para alteração'));
                return false;
            }

            if(!$rs = $mysqli->query("UPDATE mov_razao_produtos 
                                  		SET fk_produto 		=  $produto,
                                      	fk_mov_pp   		=  $fk_mov_pp->id_mov_pp,
                                      	data				= '$dataentcafe',
                                      	lote_apas 			= '$loteapasentcafe',
                                      	lote_coperativa		= '$lotecoperativaentcafe',
                                      	tipo_movimentacao	= '$tipomovimentacao',
                                      	umidade 			=  $umidadeentcafe,
                                      	bebida 				=  $bebidaentcafe,
                                      	observacao 			= '$obsevacaoentcafe',
                                      	quantidade 			=  $quantidadeentcafe
                                      	WHERE id =  $identcafe"))
            {
            	echo json_encode(array('msg'=>'Erro na alteração da entrada de café'));
                return false;
            }
			
			if(!$res = $mysqli->query("DELETE FROM mov_razao_produtos_peneira WHERE fk_razao_produtos = " . $identcafe))
	        {
	            echo json_encode(array('msg'=>'Erro na exclusão de peneiras da entrada de café'));
	            return false;
	        }
			
			if(!is_null($objlistainformacoespeneira))
            {
                $tamanho  = count($objlistainformacoespeneira) - 1;
                 
                for($i = 0 ; $i <= $tamanho; $i++)
                {
                    $idpeneiraentcafe       	= $objlistainformacoespeneira[$i]['idpeneiraentcafe'];
                    $percentualpeneiraentcafe   = str_replace(',','.',$objlistainformacoespeneira[$i]['percentualpeneiraentcafe']);
                    $sacaspeneiraentcafe      	= str_replace(',','.',$objlistainformacoespeneira[$i]['sacaspeneiraentcafe']);
                            
					if(empty($percentualpeneiraentcafe))
                    {   
                       $percentualpeneiraentcafe = 0;
                    } 
                    
					if(empty($sacaspeneiraentcafe))
                    {   
                       $sacaspeneiraentcafe = 0;
                    }
					
                    if(!$rs = $mysqli->query("INSERT INTO mov_razao_produtos_peneira(
			                                                                         fk_razao_produtos,
			                                                                         fk_peneira,
			                                                                         percentual_sacas,
			                                                                         sacas
			                                                                        ) 
		                                                                      values(
		                                                                             $identcafe,
		                                                                             $idpeneiraentcafe,
		                                                                             $percentualpeneiraentcafe,
		                                                                             $sacaspeneiraentcafe
		                                                                            )"))
                    {
                        echo json_encode(array('msg'=>'Error ao gravar peneiras da entrada de café'));
                        return false;
                    }
                     
                    $identcafepeneira = $mysqli->insert_id;
                }
            }
            
            $rs = $mysqli->query("call SP_AtualizaSaldoProdutorPropriedade($produto,$fk_mov_pp->id_mov_pp)");
			
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acaoentradacafe,'excluir'))
    {
    	
        if(isset($_POST['identcafe']))
        {
            $identcafe = $_POST['identcafe'];
        }
        else
        {
            echo json_encode(array('msg'=>'Entrada de café não identificada para exclusão'));
            return false;
        }
        
        $sql = "SELECT fk_mov_pp, fk_produto
        		FROM mov_razao_produtos
				WHERE id = $identcafe";
				
		$rs 			= $mysqli->query($sql);
		$entrada_cafe 	= $rs->fetch_object();		
		$produto		= $entrada_cafe->fk_produto;
		$propprod		= $entrada_cafe->fk_mov_pp;
		
		if(!$res = $mysqli->query("DELETE FROM mov_razao_produtos_peneira WHERE fk_razao_produtos = " . $identcafe))
        {
            echo json_encode(array('msg'=>'Erro na exclusão de peneiras da entrada de café'));
            return false;
        }
			
        if(!$res = $mysqli->query("DELETE FROM mov_razao_produtos WHERE id = " . $identcafe))
        {
            echo json_encode(array('msg'=>'Erro na exclusão da entrada de café'));
            return false;
        }
		
		$rs = $mysqli->query("call SP_AtualizaSaldoProdutorPropriedade($produto,$propprod)");
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>