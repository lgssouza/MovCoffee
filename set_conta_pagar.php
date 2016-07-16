<?php 
 
    header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['acaocontaspagar']))
    {
        $acaocontaspagar = $_POST['acaocontaspagar'];
		
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
        return false;
    }
    		 
    if(!strcmp($acaocontaspagar,'adicionar') || !strcmp($acaocontaspagar,'editar'))
    {
    	$idcontapagar				= $mysqli->real_escape_string($_POST['idcontapagar']);
		$fk_id_fornec				= $mysqli->real_escape_string($_POST['fornecpagar']);
		$documento					= $mysqli->real_escape_string($_POST['documentopagar']);
		$valor						= str_replace(',','.',$mysqli->real_escape_string($_POST['valorpagar']));
		$dataabetura				= implode("-",array_reverse(explode("/",($_POST['dataaberturapagar']))));
		$datavencimento				= implode("-",array_reverse(explode("/",($_POST['datavencimentopagar']))));
		$descricao					= $mysqli->real_escape_string($_POST['descricaopagar']);
		
        if(!strcmp($acaocontaspagar,'adicionar'))
        {           
            if(!$rs = $mysqli->query("INSERT INTO tb_conta_pagar (             
														            fk_id_fornec, 
														            descricao, 
														            documento,
														            valor,
														            data_abertura,
														            data_vencimento,
														            data_baixa,
														            forma_pagamento,
														            conta_destino
														            ) 
														            VALUES 
														            (
            														$fk_id_fornec,
            														'$descricao',
            														'$documento',
            														$valor,
            														'$dataabetura',
            														'$datavencimento',
            														NULL, 
            														NULL, 
            														NULL
            														)"))
            {	
                echo json_encode(array('msg'=>'Error ao gravar banco'));
                return false;
            }
             
            $idcontapagar = $mysqli->insert_id;
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acaocontaspagar,'editar'))
        {
            if(isset($_POST['idconta']))
            {
                $idconta 		= $_POST['idconta'];                 
            }
            else
            {
                echo json_encode(array('msg'=>'Conta não identificada para alteração'));
                return false;
            }
 
            if(!$rs = $mysqli->query("UPDATE tb_conta_pagar 
                                  		SET conta_banco = '$banco',
                                      	conta_agencia   = '$agencia',
                                      	conta_numero	= '$numero',
                                      	conta_descricao = '$descricaoconta',
                                      	conta_premio	=  $contapremio,
                                      	conta_outros	=  $contaoutros,
                                      	conta_corrente 	=  $contacorrente,
                                      	conta_aplicacao =  $contaaplicacao
                                      	WHERE id_conta_pagar 	=  $idcontapagar"))
            {
            	echo json_encode(array('msg'=>'Erro na alteração da conta'));
                return false;
            }
			
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
	elseif(!strcmp($acaocontaspagar,'excluir'))
    {
    	
        if(isset($_POST['idconta']))
        {
            $idconta = $_POST['idconta'];
        }
        else
        {
            echo json_encode(array('msg'=>'Conta não identificada para baixa'));
            return false;
        }

		$rsverifica1 	=	$mysqli->query("select a.data_baixa from tb_conta_pagar a where a.id_conta_pagar =". $idconta);				
		$verifica1 		=	$rsverifica1->fetch_object();
		
		$data_baixa = null;
		
		if(isset($verifica1->data_baixa))
		{
			$data_baixa = $verifica1->data_baixa;
		}				

		if($data_baixa == null)
		{
			if(!$res = $mysqli->query("DELETE FROM tb_conta_pagar WHERE id_conta_pagar = " . $idconta))
	        {
	            echo json_encode(array('msg'=>'Erro na exclusão da conta pagar'));
	            return false;
	        }	
		}
		else
		{
			echo json_encode(array('msg'=>'Conta a pagar já foi baixada!'));
	        return false;	
		}
		 
        echo json_encode( array ( "msg" => 'sucesso'));
    }
	elseif(!strcmp($acaocontaspagar,'baixar'))
    {	
        if(isset($_POST['idcontapagarbaixa']))
        {
            $idconta = $_POST['idcontapagarbaixa'];
        }
        else
        {
            echo json_encode(array('msg'=>'Conta não identificada para baixa'));
            return false;
        }
		
		$fk_id_conta_baixa			= $mysqli->real_escape_string($_POST['idcontabaixa']);		
		$saldoconta					= str_replace(',','.',$mysqli->real_escape_string($_POST['saldoconta']));
		$databaixa					= implode("-",array_reverse(explode("/",($_POST['databaixa']))));

		$rsverifica1 	=	$mysqli->query("select a.* from tb_conta_pagar a where a.id_conta_pagar =". $idconta);				
		$verifica1 		=	$rsverifica1->fetch_object();
		
		$data_baixa = null;
		
		if(isset($verifica1->data_baixa))
		{
			$data_baixa = $verifica1->data_baixa;
		}				

		
		if($data_baixa == null)
		{
			if($saldoconta<=$verifica1->valor)
			{
				if(!$res = $mysqli->query("UPDATE tb_conta_pagar SET data_baixa = $data_baixa WHERE id_conta_pagar =" . $idconta))
		        {
		            echo json_encode(array('msg'=>'Erro na baixa da conta pagar'));
		            return false;
		        }
			}
			else
			{
			    echo json_encode(array('msg'=>'Conta não tem saldo para efetuar a baixa'));
	            return false;
			}
			
			$res = $mysqli->query("call SP_RealizarBaixa($fk_id_conta_baixa,$idconta,$verifica1->valor,$verifica1->descricao,$data_baixa)");	
		}
		else
		{
			echo json_encode(array('msg'=>'Conta a pagar já foi baixada!'));
	        return false;	
		}
		 
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>