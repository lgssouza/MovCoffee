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
					
	if(isset($_GET['id_subgrupo']))
	{
		$idsubgvalores  = $_GET['id_subgrupo'];			
				
		if(!empty($idsubgvalores ) && $idsubgvalores  != "undefined")		 			
		{
			
			if(isset($_GET['id_item']))
			{
				$id_item = $_GET['id_item'];				
					
				if(!empty($id_item) && $id_item != "undefined")		 			
				{						
					$status = "Alteração";			
					$sql 	= "select * from tb_pdcj_item_rel WHERE id_item = " . $id_item;			
					
					$rs 	= $mysqli->query($sql);	
			
					if($rs)
					{
						$itens = array();
		        
						while($item = $rs->fetch_object())
						{
							array_push($itens, $item);
						}
				
						$rs->close();
					}
				}
			}
		}
	}
	
	$id_item 		= '';		
	$descricao		= '';		
	$valorpremio	= '';
	$valoroutros	= '';	
	$responsavel	= '';
	$data			= '';
	
	if(isset($itens))
	{
		if(isset($itens[0]->id_item))
		{
			$id_item = $itens[0]->id_item;
		}
		
		if(isset($itens[0]->fk_id_subg))
		{
			$idsubgvalores = $itens[0]->fk_id_subg;
		}
		
		if(isset($itens[0]->item_descricao))
		{
			$descricao = $itens[0]->item_descricao;			
		}
		
		if(isset($itens[0]->item_responsavel))
		{
			$responsavel = $itens[0]->item_responsavel;			
		}
				
		if(isset($itens[0]->item_valor_premio))
		{
			$valorpremio = 0;
			$valorpremio = $itens[0]->item_valor_premio;
			
			if($valorpremio!=NULL)
			{
				$valorpremio = 'R$ '.number_format($valorpremio, 2, ',', '.');
				$valorpremio 	= str_replace('.','',$valorpremio);
			}
			else
			{
				$valorpremio = 'R$ '.number_format(0, 2, ',', '.');				
			}
		}
		
		if(isset($itens[0]->item_valor_outros))
		{
			$valoroutros = 0;
			$valoroutros = $itens[0]->item_valor_outros;
			
			if($valoroutros!=NULL)
			{
				$valoroutros = 'R$ '.number_format($valoroutros, 2, ',', '.');
				$valoroutros = str_replace('.','',$valoroutros);
			}
			else
			{
				$valoroutros = 'R$ '.number_format(0, 2, ',', '.');				
			}
		}
		
		if(isset($itens[0]->item_data))
		{
			$data = $itens[0]->item_data;
			$data = date('d/m/Y', strtotime($data));
		}
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
					PDCJ
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					Lançamentos
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_lancamentos_gastos_rel.php">Gastos</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="detalhe_lancamentos_gastos_rel.php?id_subgrupo=<?php echo $idsubgvalores; ?>">Detalhes</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_lancamentos_gastos_rel.php?id_subgrupo=<?php echo $idsubgvalores.'id_item='.$id_item; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-dollar"></i>
								<?php echo $status; ?> de Gasto
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Informações do Gasto</h3>
									
								<div class="row">			
									<div class="col-md-6">
										<input type="hidden" class="form-control" id="txtiditem" value="<?php echo $id_item; ?>">
										<input type="hidden" class="form-control" id="txtidsubg" value="<?php echo $idsubgvalores; ?>">
										<label class="control-label">Descrição</label>											  
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-list"></span>													 
												</span>
												<input type="text" class="form-control" id="txtdescricao" placeholder="Digite a descrição do gasto" value="<?php echo $descricao; ?>">
											</div>
									</div>
									
									<div class="col-md-3">
										<label class="control-label">Valor - Prêmio</label>
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-money"></span>													 
											</span>
											<input type="text" class="form-control maskdinheiro" id="txtvalorpremio" placeholder="Digite o valor" value="<?php echo $valorpremio; ?>">
										</div>
									</div>
									
									<div class="col-md-3">
										<label class="control-label">Valor - Outros</label>
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-money"></span>													 
											</span>
											<input type="text" class="form-control maskdinheiro" id="txtvaloroutros" placeholder="Digite o valor" value="<?php echo $valoroutros; ?>">
										</div>
									</div>		
											
									
								</div>
								
								<br />
								
								<div class="row">
									
									<div class="col-md-6">
										<label class="control-label">Responsável</label>
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-user"></span>													 
											</span>
											<input type="text" class="form-control" id="txtresponsavel" placeholder="Digite o responsável pela ação" value="<?php echo $responsavel; ?>">
										</div>
									</div>		
									<div class="col-md-3">		
										<div class="form-group">
											<label class="control-label">Data</label>
											
											<div class="input-group date date-pickerbr">
												<span class="input-group-btn">
													<button class="btn default date-set" type="button">
														<i class="fa fa-calendar"></i>
													</button>
												</span>
												
												<input type="text" size="16" class="form-control" id="txtdata" placeholder="Digite a data do lançamento" value="<?php echo $data; ?>">
											</div>
										</div>
									</div>																									
								</div>
								
								
								
								<div id="alertcontas">
								<div>
								
							</div>
								
																																								
								
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_lancamentos_gastos_rel">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" value="<?php echo $idsubgvalores ?>" id="limpa_lancamentos_gastos_rel">
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