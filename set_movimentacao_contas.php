<?php 
 
    header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
           
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
	 
	 
    if(isset($_POST['acaomov']))
    {
        $acao = $_POST['acaomov'];
		
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
        return false;
    }
    
			 
		 
    if(!strcmp($acao,'adicionar') || !strcmp($acao,'editar'))
    {
    	
        $idmovconta = $mysqli->real_escape_string($_POST['idmovconta']);
		$idconta 	= $mysqli->real_escape_string($_POST['idconta']);
		$descricao 	= $mysqli->real_escape_string($_POST['descricao']);
		$operacao 	= $mysqli->real_escape_string($_POST['operacao']);
		$valor 		= $mysqli->real_escape_string($_POST['valor']);		
		$valor 		= str_replace(',','.',$valor);
		$data		= implode("-",array_reverse(explode("/",($_POST['data']))));		
		
        if(!strcmp($acao,'adicionar'))
        {           
            if(!$rs = $mysqli->query("INSERT INTO mov_contas (
                                                            	fk_id_conta,
                                                            	mov_contas_descricao,
                                                            	mov_contas_op,
                                                            	mov_contas_valor,
                                                            	mov_contas_data,
                                                            	mov_contas_transferencia
                                                           	  ) 
                                                     	values(
                                                    			 $idconta,
                                                             	'$descricao',
                                                              	'$operacao',
                                                              	 $valor,
                                                              	'$data',
                                                              	0                                                               
                                                           	  )"))
            {
                echo json_encode(array('msg'=>'Error ao gravar banco'));
                return false;
            }
          	
            $idmovconta = $mysqli->insert_id;
            $sp = $mysqli->query("call SP_AtualizaSaldoContaMov($idconta)");
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acao,'editar'))
        {
            if(isset($_POST['idmovconta']))
            {
                
		        $idmovconta	= $_POST['idmovconta'];
				
            }
            else
            {
                echo json_encode(array('msg'=>'Movimentação não identificada para alteração'));
                return false;
            }
 
            if(!$rs = $mysqli->query("UPDATE mov_contas 
                                  		SET mov_contas_descricao 	= '$descricao',
                                  		mov_contas_op   			= '$operacao',
                                      	mov_contas_valor			=  $valor,
                                      	mov_contas_data				= '$data'                                       	
                                      	WHERE id_mov_contas 		=  $idmovconta"))
            {            	
                echo json_encode(array('msg'=>'Erro na alteração da conta'));
                return false;
            }
			$sp = $mysqli->query("call SP_AtualizaSaldoContaMov($idconta)");
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acao,'excluir'))
    {
    	
        if(isset($_POST['idmov']))
        {
            $idmov = $_POST['idmov'];
        }
        else
        {
            echo json_encode(array('msg'=>'Movimentação não identificada para exclusão'));
            return false;
        }
		
        if(isset($_POST['idconta']))
        {
            $idconta = $_POST['idconta'];
        }
        else
        {
            echo json_encode(array('msg'=>'Conta não identificada para stored procedure'));
            return false;
        }
        
        $sqlt = "SELECT a.id_transferencia, a.id_mov1, a.id_mov2, b.fk_id_conta as conta1, c.fk_id_conta as conta2 FROM mov_contas_transferencia a inner join mov_contas as b on a.id_mov1 = b.id_mov_contas inner join mov_contas as c on a.id_mov2 = c.id_mov_contas WHERE id_mov1 = $idmov or id_mov2 = $idmov";
		
        $rs 	= $mysqli->query($sqlt);	
		
		$id_trans	= '';
		$id_mov1	= '';
		$id_mov2	= '';
		$conta1		= '';
		$conta2		= '';
		
		
		if($rs)
		{	
			$regs = array();
    
			while($reg = $rs->fetch_object())
			{
				array_push($regs, $reg);
			}
	
			$rs->close();
						
			if(isset($regs))
			{
				if(isset($regs[0]->id_transferencia))
				{
					$id_trans = $regs[0]->id_transferencia;
				}
				
				if(isset($regs[0]->id_mov1))
				{
					$id_mov1 = $regs[0]->id_mov1;
				}
			
				if(isset($regs[0]->id_mov2))
				{
					$id_mov2 = $regs[0]->id_mov2;			
				}
				
				if(isset($regs[0]->conta1))
				{
					$conta1 = $regs[0]->conta1;
				}
			
				if(isset($regs[0]->conta2))
				{
					$conta2 = $regs[0]->conta2;			
				}
			}
			
						
			if($id_trans!=null && $id_trans!=""){
			
				if(!$res = $mysqli->query("DELETE FROM mov_contas_transferencia WHERE id_transferencia = " . $id_trans))
	        	{       		
	            	echo json_encode(array('msg'=>'Erro na exclusão da transferência'));
	            	return false;
	        	}
				
				if(!$res = $mysqli->query("DELETE FROM mov_contas WHERE id_mov_contas = " . $id_mov1))
	        	{
	        		echo "DELETE FROM mov_contas WHERE id_mov_contas = " . $id_mov1;
	        		
	            	echo json_encode(array('msg'=>'Erro na exclusão da transferência'));
	            	return false;
	        	}
				
	        	$sp = $mysqli->query("call SP_AtualizaSaldoContaMov($conta1)");
	        	
	        	if(!$res = $mysqli->query("DELETE FROM mov_contas WHERE id_mov_contas = " . $id_mov2))
	        	{
	        		echo "DELETE FROM mov_contas WHERE id_mov_contas = " . $id_mov2;
	            	echo json_encode(array('msg'=>'Erro na exclusão da transferência'));
	            	return false;
	        	}
				
	        	$sp = $mysqli->query("call SP_AtualizaSaldoContaMov($conta2)");
				
				echo json_encode( array ( "msg" => 'transferencia'));
			}
			else 
			{
			
				if(!$res = $mysqli->query("DELETE FROM mov_contas WHERE id_mov_contas = " . $idmov))
		    	{
		        	echo json_encode(array('msg'=>'Erro na exclusão da movimentação'));
		        	return false;
		    	}
		    	$sp = $mysqli->query("call SP_AtualizaSaldoContaMov($idconta)");
				echo json_encode( array ( "msg" => 'sucesso'));		
			}
				
		}
		
    }
     
?>