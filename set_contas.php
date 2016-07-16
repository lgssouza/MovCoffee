<?php 
 
    header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['acaocontas']))
    {
        $acaocontas = $_POST['acaocontas'];
		
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
        return false;
    }
    		 
    if(!strcmp($acaocontas,'adicionar') || !strcmp($acaocontas,'editar'))
    {
    	$idconta		= $mysqli->real_escape_string($_POST['idconta']);
        $banco 			= $mysqli->real_escape_string($_POST['banco']);
		$agencia 		= $mysqli->real_escape_string($_POST['agencia']);
		$numero 		= $mysqli->real_escape_string($_POST['numero']);
		$descricaoconta = $mysqli->real_escape_string($_POST['descricaoconta']);
		$contapremio 	= $mysqli->real_escape_string($_POST['contapremio']);
		$contaoutros 	= $mysqli->real_escape_string($_POST['contaoutros']);
		$contacorrente 	= $mysqli->real_escape_string($_POST['contacorrente']);
		$contaaplicacao = $mysqli->real_escape_string($_POST['contaaplicacao']);
				
		if(!strcmp($acaocontas,'adicionar'))
		{
			$rsverifica1 = $mysqli->query("select COUNT(*) as total from tb_conta where conta_premio = 1 && conta_corrente = 1");
			$rsverifica2 = $mysqli->query("select COUNT(*) as total from tb_conta where conta_premio = 1 && conta_aplicacao = 1");
			$rsverifica3 = $mysqli->query("select COUNT(*) as total from tb_conta where conta_outros = 1 && conta_corrente = 1");
			$rsverifica4 = $mysqli->query("select COUNT(*) as total from tb_conta where conta_outros = 1 && conta_aplicacao = 1");
		}
		else		
		{
			$rsverifica1 = $mysqli->query("select COUNT(*) as total from tb_conta where conta_premio = 1 && conta_corrente = 1 && id_conta != $idconta");
			$rsverifica2 = $mysqli->query("select COUNT(*) as total from tb_conta where conta_premio = 1 && conta_aplicacao = 1 && id_conta != $idconta");
			$rsverifica3 = $mysqli->query("select COUNT(*) as total from tb_conta where conta_outros = 1 && conta_corrente = 1 && id_conta != $idconta");
			$rsverifica4 = $mysqli->query("select COUNT(*) as total from tb_conta where conta_outros = 1 && conta_aplicacao = 1 && id_conta != $idconta");		
		}
	
		$verifica1 	=	$rsverifica1->fetch_object();		
		$cont1		= 	$verifica1->total;
		if($cont1==1)
		{
			if($contapremio==1 && $contacorrente==1)
			{
				echo json_encode(array('msg' => 'validacaopremio1'));
				return false;
			}
			
		}		
			
		$verifica2 	=	$rsverifica2->fetch_object();		
		$cont2		= 	$verifica2->total;
		if($cont2==1)
		{
			if($contapremio==1 && $contaaplicacao==1)
			{
				echo json_encode(array('msg' => 'validacaopremio2'));
				return false;
			}
			
		}	
		
		$verifica3 	=	$rsverifica3->fetch_object();		
		$cont3		= 	$verifica3->total;
		if($cont3==1)
		{
			if($contaoutros==1 && $contacorrente==1)
			{
				echo json_encode(array('msg' => 'validacaooutros1'));
				return false;
			}
			
		}
		
		$verifica4 	=	$rsverifica4->fetch_object();		
		$cont4		= 	$verifica4->total;
		if($cont4==1)
		{
			if($contaoutros==1 && $contaaplicacao==1)
			{
				echo json_encode(array('msg' => 'validacaooutros2'));
				return false;
			}
			
		}	
		

        if(!strcmp($acaocontas,'adicionar'))
        {           
            if(!$rs = $mysqli->query("INSERT INTO tb_conta (
                                                            	conta_banco,
                                                            	conta_agencia,
                                                            	conta_numero,
                                                            	conta_descricao,
                                                            	conta_premio,
                                                            	conta_outros,
                                                            	conta_corrente,
                                                            	conta_aplicacao
                                                           	  ) 
                                                     	values(
                                                        		'$banco',
                                                             	'$agencia',
                                                              	'$numero',
                                                              	'$descricaoconta',
                                                          		 $contapremio,
                                                              	 $contaoutros,
                                                              	 $contacorrente,
                                                              	 $contaaplicacao                                                              
                                                           	  )"))
            {
                echo json_encode(array('msg'=>'Error ao gravar banco'));
                return false;
            }
             
            $idbanco = $mysqli->insert_id;
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acaocontas,'editar'))
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
 
            if(!$rs = $mysqli->query("UPDATE tb_conta 
                                  		SET conta_banco = '$banco',
                                      	conta_agencia   = '$agencia',
                                      	conta_numero	= '$numero',
                                      	conta_descricao = '$descricaoconta',
                                      	conta_premio	=  $contapremio,
                                      	conta_outros	=  $contaoutros,
                                      	conta_corrente 	=  $contacorrente,
                                      	conta_aplicacao =  $contaaplicacao
                                      	WHERE id_conta 	=  $idconta"))
            {
            	echo json_encode(array('msg'=>'Erro na alteração da conta'));
                return false;
            }
			
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acaocontas,'excluir'))
    {
    	
        if(isset($_POST['idconta']))
        {
            $idconta = $_POST['idconta'];
        }
        else
        {
            echo json_encode(array('msg'=>'Conta não identificada para exclusão'));
            return false;
        }

		$rsverifica1 	=	$mysqli->query("SELECT conta_saldo FROM tb_conta_saldo WHERE fk_id_conta =". $idconta);				
		$verifica1 		=	$rsverifica1->fetch_object();
		
		$saldo = 0;
				
		if(isset($verifica1->conta_saldo))
		{
			$saldo = $verifica1->conta_saldo;
		}				

		if($saldo == 0)
		{
			if(!$res = $mysqli->query("DELETE FROM tb_conta_saldo WHERE fk_id_conta = " . $idconta))
	        {
	            echo json_encode(array('msg'=>'Erro na exclusão do saldo da conta'));
	            return false;
	        }	
		}
		         
        if(!$res = $mysqli->query("DELETE FROM tb_conta WHERE id_conta = " . $idconta))
        {
            echo json_encode(array('msg'=>'Erro na exclusão da conta'));
            return false;
        }
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>