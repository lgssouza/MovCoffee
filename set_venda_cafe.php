<?php 
 
    header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['acaovendacafe']))
    {
        $acaovendacafe = $_POST['acaovendacafe'];
		
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
        return false;
    }
    		 
    if(!strcmp($acaovendacafe,'adicionar') || !strcmp($acaovendacafe,'editar'))
    {		
		$idvendacafe				= $mysqli->real_escape_string($_POST['idvendacafe']);
		$produtorvenda				= $mysqli->real_escape_string($_POST['produtorvenda']);
		$propriedadevenda			= $mysqli->real_escape_string($_POST['propriedadevenda']);
		$descpropriedadevenda		= $mysqli->real_escape_string($_POST['descpropriedadevenda']);
		$datavendacafe				= implode("-",array_reverse(explode("/",($_POST['datavendacafe']))));	 
		$loteapasvendacafe			= $mysqli->real_escape_string($_POST['loteapasvendacafe']);
		$lotecoperativavendacafe	= $mysqli->real_escape_string($_POST['lotecoperativavendacafe']);
		$compradorvendacafe			= $mysqli->real_escape_string($_POST['compradorvendacafe']);
		$bebidavendacafe			= str_replace(',','.',$mysqli->real_escape_string($_POST['bebidavendacafe']));
		$tipovendacafe				= str_replace(',','.',$mysqli->real_escape_string($_POST['tipovendacafe']));
		$quantidadevendacafe		= str_replace(',','.',$mysqli->real_escape_string($_POST['quantidadevendacafe']));
		$peneiravenda				= $mysqli->real_escape_string($_POST['peneiravenda']);
		$percentualpeneiravendacafe	= str_replace(',','.',$mysqli->real_escape_string($_POST['percentualpeneiravendacafe']));
		$sacasprontasvendacafe		= str_replace(',','.',$mysqli->real_escape_string($_POST['sacasprontasvendacafe']));
		$sacasfundovendacafe		= str_replace(',','.',$mysqli->real_escape_string($_POST['sacasfundovendacafe']));
		$valorsacaspreparavendacafe	= str_replace(',','.',$mysqli->real_escape_string($_POST['valorsacaspreparavendacafe']));
		$valorsacasfundovendacafe	= str_replace(',','.',$mysqli->real_escape_string($_POST['valorsacasfundovendacafe']));
		$valortotalvendacafe		= str_replace(',','.',$mysqli->real_escape_string($_POST['valortotalvendacafe']));
		$obsevacaovendacafe			= $mysqli->real_escape_string($_POST['obsevacaovendacafe']);
		$tipomovimentacao			= $mysqli->real_escape_string($_POST['tipomovimentacao']);
		$produto 					= $mysqli->real_escape_string($_POST['produto']);
		$fairtrade 					= $mysqli->real_escape_string($_POST['fairtrade']);
		
		if(empty($bebidavendacafe))
		{
			$bebidavendacafe = 'NULL';
		}

		if(empty($tipovendacafe))
		{
			$tipovendacafe = 'NULL';
		}
				
		$sql = "SELECT id_mov_pp FROM mov_prop_prod
				WHERE fk_id_prop = $propriedadevenda 
				AND fk_id_prod = $produtorvenda";
			
		$rs 		= $mysqli->query($sql);
		$fk_mov_pp 	= $rs->fetch_object();
				
		$sql = "SELECT fk_peneira FROM mov_razao_produtos_peneira
				WHERE id = $peneiravenda";
				
		$rs 		= $mysqli->query($sql);
		$fk_peneira = $rs->fetch_object();
						
       	if(!strcmp($acaovendacafe,'adicionar'))
        {       	
            if(!$rs = $mysqli->query("INSERT INTO mov_razao_produtos (
		                                                            	fk_produto,
		                                                            	fk_mov_pp,
		                                                            	data,
		                                                            	lote_apas,
		                                                            	lote_coperativa,
		                                                            	tipo_movimentacao,
		                                                            	bebida,
		                                                            	observacao,
		                                                            	quantidade
		                                                           	  ) 
		                                                     	values(
		                                                        		 $produto,
		                                                             	 $fk_mov_pp->id_mov_pp,
		                                                              	'$datavendacafe',
		                                                              	'$loteapasvendacafe',
		                                                              	'$lotecoperativavendacafe',
		                                                          		'$tipomovimentacao',
		                                                              	 $bebidavendacafe,
		                                                                '$obsevacaovendacafe',
		                                                              	 $quantidadevendacafe                                                              
		                                                           	  )"))
		    {	
                echo json_encode(array('msg'=>'Error ao gravar venda de café'));
                return false;
            }
             
            $idvendacafe = $mysqli->insert_id;
          	
          	if(!$rs = $mysqli->query("INSERT INTO mov_venda(
                                                             fk_razao_produtos,
                                                             comprador,
                                                             tipo_cafe,
                                                             fairtrade,
                                                             sacas_prontas,
                                                             sacas_fundo,
                                                             valor_saca_preparada,
                                                             valor_saca_fundo,
                                                             valor_total
                                                            ) 
                                                      values(
                                                              $idvendacafe,
                                                             '$compradorvendacafe',
                                                              $tipovendacafe,
                                                              $fairtrade,
                                                              $sacasprontasvendacafe,
                                                              $sacasfundovendacafe,
                                                              $valorsacaspreparavendacafe,
                                                              $valorsacasfundovendacafe,
                                                              $valortotalvendacafe
                                                            )"))
            {
                echo json_encode(array('msg'=>'Error ao gravar movimentação venda da venda de café'));
                return false;
            }
             
            $idmovvenda = $mysqli->insert_id;
          	
            if(!$rs = $mysqli->query("INSERT INTO mov_razao_produtos_peneira(
	                                                                         fk_razao_produtos,
	                                                                         fk_peneira,
	                                                                         percentual_sacas,
	                                                                         sacas
	                                                                        ) 
                                                                      values(
                                                                             $idvendacafe,
                                                                             $fk_peneira->fk_peneira,
                                                                             $percentualpeneiravendacafe,
                                                                             $sacasprontasvendacafe
                                                                            )"))
            {
                echo json_encode(array('msg'=>'Error ao gravar peneiras da entrada de café'));
                return false;
            }
             
            $idvendacafepeneira = $mysqli->insert_id;
            
            $rs = $mysqli->query("call SP_AtualizaSaldoProdutorPropriedade($produtorvenda,$fk_mov_pp->id_mov_pp)");
                        
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acaovendacafe,'editar'))
        {
            if(isset($_POST['idvendacafe']))
            {
                $idvendacafe = $_POST['idvendacafe'];                 
            }
            else
            {
                echo json_encode(array('msg'=>'Venda de café não identificada para alteração'));
                return false;
            }

            if(!$rs = $mysqli->query("UPDATE mov_razao_produtos 
                                  		SET fk_produto 		=  $produtorvenda,
                                      	fk_mov_pp   		=  $fk_mov_pp->id_mov_pp,
                                      	data				= '$datavendacafe',
                                      	lote_apas 			= '$loteapasvendacafe',
                                      	lote_coperativa		= '$lotecoperativavendacafe',
                                      	tipo_movimentacao	= '$tipomovimentacao',
                                      	bebida 				=  $bebidavendacafe,
                                      	observacao 			= '$obsevacaovendacafe',
                                      	quantidade 			=  $quantidadevendacafe
                                      	WHERE id =  $idvendacafe"))
            {
            	echo json_encode(array('msg'=>'Erro na alteração da venda de café'));
                return false;
            }
			
			if(!$res = $mysqli->query("DELETE FROM mov_razao_produtos_peneira WHERE fk_razao_produtos = " . $idvendacafe))
	        {
	            echo json_encode(array('msg'=>'Erro na exclusão de peneiras da venda de café'));
	            return false;
	        }

			if(!$res = $mysqli->query("DELETE FROM mov_venda WHERE fk_razao_produtos = " . $idvendacafe))
	        {
	            echo json_encode(array('msg'=>'Erro na exclusão da movimentação venda da venda de café'));
	            return false;
	        }
			
			if(!$rs = $mysqli->query("INSERT INTO mov_venda(
                                                             fk_razao_produtos,
                                                             comprador,
                                                             tipo_cafe,
                                                             fairtrade,
                                                             sacas_prontas,
                                                             sacas_fundo,
                                                             valor_saca_preparada,
                                                             valor_saca_fundo,
                                                             valor_total
                                                            ) 
                                                      values(
                                                              $idvendacafe,
                                                             '$compradorvendacafe',
                                                              $tipovendacafe,
                                                              $fairtrade,
                                                              $sacasprontasvendacafe,
                                                              $sacasfundovendacafe,
                                                              $valorsacaspreparavendacafe,
                                                              $valorsacasfundovendacafe,
                                                              $valortotalvendacafe
                                                            )"))
            {
                echo json_encode(array('msg'=>'Error ao gravar movimentação venda da venda de café'));
                return false;
            }
             
            $idmovvenda = $mysqli->insert_id;
          	
            if(!$rs = $mysqli->query("INSERT INTO mov_razao_produtos_peneira(
	                                                                         fk_razao_produtos,
	                                                                         fk_peneira,
	                                                                         percentual_sacas,
	                                                                         sacas
	                                                                        ) 
                                                                      values(
                                                                             $idvendacafe,
                                                                             $fk_peneira->fk_peneira,
                                                                             $percentualpeneiravendacafe,
                                                                             $sacasprontasvendacafe
                                                                            )"))
            {
                echo json_encode(array('msg'=>'Error ao gravar peneiras da entrada de café'));
                return false;
            }
             
            $idvendacafepeneira = $mysqli->insert_id;
            
            $rs = $mysqli->query("call SP_AtualizaSaldoProdutorPropriedade($produtorvenda,$fk_mov_pp->id_mov_pp)");
			
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acaovendacafe,'excluir'))
    {
    	
        if(isset($_POST['idvendacafe']))
        {
            $idvendacafe = $_POST['idvendacafe'];
        }
        else
        {
            echo json_encode(array('msg'=>'Venda de café não identificada para exclusão'));
            return false;
        }
        
        $sql = "SELECT fk_mov_pp, fk_produto
        		FROM mov_razao_produtos
				WHERE id = $idvendacafe";
				
		$rs 			= $mysqli->query($sql);
		$entrada_cafe 	= $rs->fetch_object();		
		$produto		= $entrada_cafe->fk_produto;
		$propprod		= $entrada_cafe->fk_mov_pp;
		
		if(!$res = $mysqli->query("DELETE FROM mov_razao_produtos_peneira WHERE fk_razao_produtos = " . $idvendacafe))
        {
            echo json_encode(array('msg'=>'Erro na exclusão de peneiras da venda de café'));
            return false;
        }
        
        if(!$res = $mysqli->query("DELETE FROM mov_venda WHERE fk_razao_produtos = " . $idvendacafe))
        {
            echo json_encode(array('msg'=>'Erro na exclusão da movimentação venda da venda de café'));
            return false;
        }
			
        if(!$res = $mysqli->query("DELETE FROM mov_razao_produtos WHERE id = " . $idvendacafe))
        {
            echo json_encode(array('msg'=>'Erro na exclusão da venda de café'));
            return false;
        }
		
		$rs = $mysqli->query("call SP_AtualizaSaldoProdutorPropriedade($produto,$propprod)");
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>