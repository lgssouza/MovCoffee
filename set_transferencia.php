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
    
		 
    if(!strcmp($acao,'transferir'))
    {
    	$idconta1 	= $mysqli->real_escape_string($_POST['idconta1']);
		$idconta2 	= $mysqli->real_escape_string($_POST['idconta2']);		
		$valor 		= $mysqli->real_escape_string($_POST['valor']);		
		$valor 		= str_replace(',','.',$valor);
		$data		= implode("-",array_reverse(explode("/",($_POST['data']))));
		
		
		$rsverifica1 	=	$mysqli->query("SELECT conta_saldo FROM tb_conta_saldo WHERE fk_id_conta =". $idconta1);		
		$verifica1 		=	$rsverifica1->fetch_object();
		
		$saldo = 0;
				
		if(isset($verifica1->conta_saldo))
		{
			$saldo = $verifica1->conta_saldo;
		}
		
				
		if($saldo<$valor)
		{	
			echo json_encode(array('msg' => 'validacaotransferencia1'));
			return false;
		}
			          
        if(!$rs = $mysqli->query("INSERT INTO mov_contas (
                                                        	fk_id_conta,
                                                        	mov_contas_descricao,
                                                        	mov_contas_op,
                                                        	mov_contas_valor,
                                                        	mov_contas_data,
                                                        	mov_contas_transferencia
                                                       	  ) 
                                                 	values(
                                                			 $idconta1,
                                                         	'transferência',
                                                          	'S',
                                                          	 $valor,
                                                          	'$data',
                                                          	1                                                               
                                                       	  )"))
        {
            echo json_encode(array('msg'=>'Error ao gravar banco'));
            return false;
        }
        $idmovconta1 = $mysqli->insert_id;
        $sp = $mysqli->query("call SP_AtualizaSaldoContaMov($idconta1)");
        
        if(!$rs = $mysqli->query("INSERT INTO mov_contas (
                                                        	fk_id_conta,
                                                        	mov_contas_descricao,
                                                        	mov_contas_op,
                                                        	mov_contas_valor,
                                                        	mov_contas_data,
                                                        	mov_contas_transferencia
                                                       	  ) 
                                                 	values(
                                                			 $idconta2,
                                                         	'transferencia',
                                                          	'E',
                                                          	 $valor,
                                                          	'$data',
                                                          	1                                                               
                                                       	  )"))
        {
            echo json_encode(array('msg'=>'Error ao gravar banco'));
            return false;
        }
      	
        $idmovconta2 = $mysqli->insert_id;        
        $sp = $mysqli->query("call SP_AtualizaSaldoContaMov($idconta2)");
        
        if(!$rs = $mysqli->query("INSERT INTO mov_contas_transferencia (
                                                        	id_mov1,
                                                        	id_mov2
                                                       	  ) 
                                                 	values(
                                                			 $idmovconta1,
                                			                 $idmovconta2                                               
                                                       	  )"))
        {
            echo json_encode(array('msg'=>'Error ao gravar banco'));
            return false;
        }
        
        echo json_encode(array('msg' => 'sucesso')); 
       	
        
    }
     
?>