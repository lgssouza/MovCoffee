<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	//include_once('verificaLogin.php');
	//include_once('verificapermissao.php');
	
	//if(!verificapermissao("Categorias de Funcionário",$permissoes))
	//{
	//	header("Location: home.php");  
	//	exit;
	//}	
	
	include_once('connect.php');
	
	global $mysqli;
		
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	$status = "Inclusão";
					
	if(isset($_GET['id_conta']))
	{
		$id_conta = $_GET['id_conta'];			
				
		if(!empty($id_conta) && $id_conta != "undefined")		 			
		{
			
			if(isset($_GET['idmov']))
			{
				$idmov = $_GET['idmov'];				
					
				if(!empty($idmov) && $idmov != "undefined")		 			
				{						
					$status = "Alteração";			
					$sql 	= "SELECT * FROM `mov_contas` WHERE id_mov_contas = " . $idmov;			
					
					$rs 	= $mysqli->query($sql);	
			
					if($rs)
					{
						$contas = array();
		        
						while($conta = $rs->fetch_object())
						{
							array_push($contas, $conta);
						}
				
						$rs->close();
					}
				}
			}
		}
	}
	
	$id_mov 	= '';		
	$descricao 	= '';
	$operacao 	= '';
	$valor 		=  0;
	$data 		= '';
	
	if(isset($contas))
	{
		if(isset($contas[0]->id_mov_contas))
		{
			$id_mov = $contas[0]->id_mov_contas;
		}
		
		if(isset($contas[0]->fk_id_conta))
		{
			$id_conta = $contas[0]->fk_id_conta;
		}
		
		if(isset($contas[0]->mov_contas_descricao))
		{
			$descricao = $contas[0]->mov_contas_descricao;
		}
		
		if(isset($contas[0]->mov_contas_op))
		{
			$operacao = $contas[0]->mov_contas_op;
		}
		
		if(isset($contas[0]->mov_contas_valor))
		{
			$valor = $contas[0]->mov_contas_valor;
		}	
		
		if(isset($contas[0]->mov_contas_data))
		{
			$data = date('d/m/Y', strtotime($contas[0]->mov_contas_data));
			
		}		
	}
		
	$datahj = date('d/m/Y');
	
	if($valor!=0)
	{
		$valor = number_format($valor, 2, ',', '.');	
		$valor 	= str_replace('.','',$valor);
	}
	else
	{
		$valor = number_format(0, 2, ',', '.');
	}
	

?>

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
		
		<!-- BEGIN MENSAGENS-->
		<div class="caixa_mensagens"></div>	
		<!-- END MENSAGENS-->
		
		<!-- BEGIN PAGE HEADER-->		
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="inicio.php">Início</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					Financeiro
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_movimentacao_contas.php">Saldo Contas</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="detalhe_movimentacao_contas.php?id_conta=<?php echo $id_conta; ?>">Movimentação</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_movimentacao_contas.php?id_conta=<?php echo $id_conta.'idmov='.$id_mov; ?>"><?php echo $status; ?></a>
				</li>
				
			</ul>
		</div>
		<!-- END PAGE HEADER-->
		
		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12">
				<div class="tab-pane" id="tab_1">
					<div class="portlet box green-jungle">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-credit-card"></i>
								<?php echo $status; ?> de Movimento Bancário
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Dados</h3>
									
								<div class="row">																	
									<div class="col-md-12">
										
										<input type="hidden" class="form-control" id="txtidconta" value="<?php echo $id_conta; ?>">
										<input type="hidden" class="form-control" id="txtidmovconta" value="<?php echo $id_mov; ?>">
										
										
										<label class="control-label">Descrição</label>											  
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-list"></span>													 
											</span>
											<input type="text" class="form-control" id="txtdescricao" placeholder="Digite a descrição da movimentação" value="<?php echo $descricao; ?>">
										</div>
									</div>	
								</div>
								
								<br />
								
								<div class="row">
									
									<div class="col-md-4">
										<label class="control-label">Operação</label>
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-exchange"></span>													 
											</span>
											<select id="cmboperacao" class="form-control select2me" data-placeholder="Selecione...">
												<option value=""></option>
												<?php 
													if($operacao=='E')
													{
														echo "<option selected value=".'E'.">Entrada</option>";
														echo "<option value=".'S'.">Saída</option>";
													}
													elseif ($operacao=='S') 
													{
														echo "<option value=".'E'.">Entrada</option>";
														echo "<option selected value=".'S'.">Saída</option>";
													} 
													else
													{													
														echo "<option value=".'E'.">Entrada</option>";
														echo "<option value=".'S'.">Saída</option>";
													}
													 
												?>											
												
											</select>									
										</div>
									</div>			
									
									<div class="col-md-4">
										<label class="control-label">Valor</label>
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-money"></span>													 
											</span>
											<input type="text" class="form-control maskdinheiro" id="txtvalor" placeholder="Digite o valor" value="<?php echo $valor; ?>">
										</div>
									</div>	
									
									<div class="col-md-4">		
										<div class="form-group">
											<label class="control-label">Data</label>
											
											<div class="input-group date date-pickerbr">
												<span class="input-group-btn">
													<button class="btn default date-set" type="button">
														<i class="fa fa-calendar"></i>
													</button>
												</span>
												
												<input type="text" size="16" class="form-control" id="txtdata" placeholder="Digite a data do lançamento" value="<?php if($status=='Inclusão'){echo $datahj;}else{echo $data;} ?>">
												
											</div>
										</div>
									</div>	
									
								</div>
																								
								<div id="alertcontas">
								<div>								
							</div>
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_mov_contas">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" value="<?php echo $id_conta ?>" id="limpa_mov_contas">
									<i class="fa fa-remove"></i>
									&nbsp;Limpar
								</button>
							</div>							
							<!-- END FORM -->
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		
	</div>
</div>
<!-- END CONTENT -->

<?php

	$mysqli->close();
	
?>