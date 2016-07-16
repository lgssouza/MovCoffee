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
	
	$pagina = 1; 
	
	if(isset($_GET['pagina']))
	{
		if(!empty($_GET['pagina']) && $_GET['pagina'] != "undefined")
		{
			$pagina = $_GET['pagina'];
		}
	}
	
	$registros = 10;

	if(isset($_GET['registros']))
	{
		if(!empty($_GET['registros']) && $_GET['registros'] != "undefined")
		{
			$registros = $_GET['registros'];
		}
	}
	
    $inicial   = ($pagina-1) * $registros;
    $rs        = $mysqli->query('select count(*) 
								 from tb_conta a left join tb_conta_saldo b on a.id_conta = b.fk_id_conta');
	
	if($rs)
	{
		$totalregistros 	= $rs->fetch_row();
		$totalregistros 	= $totalregistros[0];
		$divisaoregistros 	= $totalregistros / 5;
		$partefracionada 	= $divisaoregistros-floor($divisaoregistros);
		$parteinteira 		= floor($divisaoregistros);
		
		if($partefracionada > 0.0)
		{
			$divisaoregistros	= $parteinteira * 5 + 5;
		}
		else
	    {
			$divisaoregistros	= $parteinteira * 5;
		}
		
		$totalpaginas 		= $divisaoregistros / $registros;
		$partefracionadapag = $totalpaginas-floor($totalpaginas);
		$parteinteirapag	= floor($totalpaginas);
		
		if($partefracionadapag > 0.0)
		{
			$totalpaginas	= $parteinteirapag + 1;
		}
		else
	    {
			$totalpaginas	= $parteinteirapag;
		}		
	}
	
	if($inicial<0)
	{
		$inicial = 0;
		$pagina	 = 1;
	}
		
	$sql 	= "select a.id_conta, a.conta_banco, a.conta_agencia, a.conta_numero, b.fk_id_conta, b.conta_saldo from tb_conta a left join tb_conta_saldo b on a.id_conta = b.fk_id_conta  
			   limit $inicial, $registros";
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
	
?>

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
		
		<!-- BEGIN MENSAGENS-->
		<?php
			if(isset($_GET['msg']))
			{
				if(!empty($_GET['msg']))
				{
					if($_GET['tipo_msg'] == "sucesso")
					{
		?>
		
						<div class="alert alert-success">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
							<strong>Sucesso!</strong> 
							<?php echo $_GET['msg'];?>
						</div>
		
		<?php
					}
					else if($_GET['tipo_msg'] == "erro")
					{
		?>
		
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
							<strong>Erro!</strong> 
							<?php echo $_GET['msg'];?>
						</div>
		
		<?php
					}
				}
			}
		?>
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
				</li>
			</ul>
			
			<div class="page-toolbar">
				<div class="btn-toolbar">
					<div class="btn-group pull-right">	
						<button style="width: 150px" type="button" class="btn btn-fit-height default green-jungle" id="btn_transferencia">
							<i class="fa fa-money"></i>
							&nbsp;Transferência 						
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE HEADER-->
		
		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12">
				
				<!-- BEGIN SAMPLE TABLE PORTLET-->
				<div class="portlet box green-jungle">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-credit-card"></i>Saldo de Contas
						</div>
						
						<div class="tools">
							<!-- <a href="javascript:;" class="collapse"></a>-->
						</div>
					</div>
					
					<div class="portlet-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="tbl_movimentacao_contas">
								<thead>
									<tr>				
										<th style="display:none;">Código</th>												
		          						<th>Banco</th>
		          						<th>Agência</th>
		          						<th>Conta</th>
		          						<th>Saldo</th>
		          						
		          						<?php
						          			//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
						          			//{
						          				echo '<th style="text-align: center">Abrir</th>';
											//}
										?>
						          		
						          		<?php
						          			//if(verificapermissaoexclusao("Categorias de Funcionário",$permissoes))
						          			//{
						          				//echo '<th style="text-align: center">Excluir</th>';
											//}
										?>
																	
									</tr>
								</thead>
								
								<tbody>
									
									<?php
										      
										for($i=0;$i<count($contas);$i++)
										{
											$saldo = 0;
											if($contas[$i]->conta_saldo!=NULL)
											{
												$saldo = 'R$ '.number_format($contas[$i]->conta_saldo, 2, ',', '.');
											}
											else
											{
												$saldo = 'R$ '.number_format(0, 2, ',', '.');
												
											}
											echo	'<tr>									
														<td style="display:none;">'.$contas[$i]->id_conta.'</td>	          		
														<td>'.$contas[$i]->conta_banco.'</td>
														<td>'.$contas[$i]->conta_agencia.'</td>
														<td>'.$contas[$i]->conta_numero.'</td>
														<td>'.$saldo.'</td>';
														
														//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="fa fa-external-link" id="'.$contas[$i]->id_conta.'" style="color:green;width:100%;height:100%"></span>
																		</a>
																	</td>';
														//}
							
											echo	'</tr>';
										}
											
							    	?>
							    	
								</tbody>
							</table>
						</div>
					
						<?php
							
							/** Inclui Função Paginação **/
							include_once('Funcoes/paginacao.php');
							
							/** Imprime Paginação **/
							echo paginacao_php(
												$pagina,					 /** Pagina atual **/
												$registros,					 /** Quantidade de registros por paginação  **/
												$totalpaginas,				 /** Total de paginas **/
												$totalregistros,			 /** Total de registros **/
												'consultamovimentacaocontas', /** Nome da função javascrip arquivo 'funcoes.js' **/
												'contas'		 /** Descrição que aparecera na paginação **/
											  );
							
						?>
					
					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->
				
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		<!-- INICIO FIM MODAL DE CONSULTA -->
		<div id="modal_transferencia" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Transferência de Valores entre Contas
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							
							<?php
								if(isset($_GET['msg_modal']))
								{
									if(!empty($_GET['msg_modal']))
									{
										if($_GET['msg_modal'] == "erromodal")
										{
											echo "<div class=\"alert alert-danger\">
												<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"></button>
												<strong>Erro!</strong>								
											</div>";							
										}
									}
								}
							?>
							
							<div class="row">										
								<div class="col-md-12">
									<div class="form-group">	
										<label class="control-label">Transferir de:</label>
										
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-credit-card"></span>													 
											</span>
											
											<select class="form-control select2me" id="cmbconta1">	
												<option selected value="nenhum">Selecione uma conta...</option>	
																				
												<?php 
												
													$sql 			= "SELECT * FROM `tb_conta`";
													$rsfiltro	= $mysqli->query($sql);
													$sql 			= "";
										
													if($rsfiltro)
													{
														$regs = array();
										        
														while($reg = $rsfiltro->fetch_object())
														{
															array_push($regs, $reg);
															
														}
												
														$rsfiltro->close();
														
														for($i=0;$i<count($regs);$i++)
														{
																echo "<option value=".$regs[$i]->id_conta.">".$regs[$i]->conta_banco." - ".$regs[$i]->conta_descricao."</option>";
														}
													}
																								
												?>
											</select>
										</div>
									</div>				
								</div>
							</div>										
							<div class="row">										
								<div class="col-md-12">
									<div class="form-group">	
										<label class="control-label">Para conta:</label>
										
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-credit-card"></span>													 
											</span>
											
											<select class="form-control select2me" id="cmbconta2">	
												<option selected value="nenhum">Selecione uma conta...</option>	
																				
												<?php 
												
													$sql 			= "SELECT * FROM `tb_conta`";
													$rsfiltro	= $mysqli->query($sql);
													$sql 			= "";
										
													if($rsfiltro)
													{
														$regs = array();
										        
														while($reg = $rsfiltro->fetch_object())
														{
															array_push($regs, $reg);
															
														}
												
														$rsfiltro->close();
														
														for($i=0;$i<count($regs);$i++)
														{
																echo "<option value=".$regs[$i]->id_conta.">".$regs[$i]->conta_banco." - ".$regs[$i]->conta_descricao."</option>";
														}
													}
																								
												?>
											</select>
										</div>
									</div>				
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label class="control-label">Valor</label>
									<div class="input-group">
										<span class="input-group-addon">
											<span class="fa fa-money"></span>													 
										</span>
										<input type="text" class="form-control maskdinheiro" id="txtvalortransferir" placeholder="Digite o valor">
									</div>
								</div>	
								<div class="col-md-4">
									<label class="control-label">Data</label>
									
									<div class="input-group date date-pickerbr">
										<span class="input-group-btn">
											<button class="btn default date-set" type="button">
												<i class="fa fa-calendar"></i>
											</button>
										</span>
										
										<input type="text" size="16" class="form-control" id="txtdataransferir" placeholder="Digite a data do lançamento">
										
									</div>
								</div>
							</div>								
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_transferir">
							<i class="fa fa-money"></i>
							&nbsp;Transferir
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_transferencia">
							<i class="fa fa-remove"></i>
							&nbsp;Limpar
						</button>
					</div>	
				</div>
			</div>
		</div>
		<!-- FIM MODAL DE CONSULTA-->		
	</div>
</div>
<!-- END CONTENT -->

<?php

	$mysqli->close();
	
?>