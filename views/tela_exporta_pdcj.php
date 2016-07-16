<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
	
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
	
	$sql 			= "SELECT * FROM `tb_relatorio` WHERE rel_tipo = 1";
	$rsrelatorios 	= $mysqli->query($sql);
	$sql 			= "";
	
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
					Exportação
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="tela_exporta_pdcj.php?">Planilhas</a>
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
								<i class="fa fa-print"></i>
								Exportação de Planilhas
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Filtros da Exportação</h3>
									
								<div class="row">
									<div class="col-md-9">
										<div class="form-group">
											<label class="control-label">Planilha</label>
																								
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-bookmark"></span> 
												</span>
																							  
												<select class="form-control select2me" id="cmbrelatorio" tabindex="1">
													<option value="" default>Selecione uma planilha</option>
													
													<?php
														
														if($rsrelatorios)
														{																													
															$relatorios = array();
    
															while($relatorio = $rsrelatorios->fetch_object())
															{
																array_push($relatorios, $relatorio);
															}
															
															$rsrelatorios->close();
															
															for($i=0;$i<count($relatorios);$i++)
															{	
																echo '"<option value="'.$relatorios[$i]->rel_descricao.'">'.$relatorios[$i]->rel_descricao.'</option>"';
															}
														}
														
													?>
												</select>
											</div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Ano</label>
											
											<div class="input-group date date-pickerano">
												<span class="input-group-addon">
													<span class="fa fa-calendar"></span>													 
												</span>
												
												<input type="text" class="form-control" id="txtanorel" placeholder="Ano" value="">
											</div>
										</div>
									</div>		
								</div>
							</div>
								
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="btn_exp_relatorio_pdcj">
									<i class="fa fa-print"></i>
									&nbsp;Exportar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="btn_limpa_exp_relatorio_pdcj">
									<i class="fa fa-remove"></i>
									&nbsp;Limpar
								</button>
							</div>							
							<!-- END FORM -->
							
							<!-- BEGIN PROGRESS BAR-->
							<div id="modal-progress-bar" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
								<div class="modal-body">
									<div class="row" style="margin-top: 230px">
										<div class="col-md-2"></div>
										
										<div class="col-md-8">
											<h3 id="labelProgressBar" style="width:100%;text-align:center;color:#ffffff;font-weight:bold">
												EXPORTANDO PLANILHA PDCJ
											</h3>
											
											<div class="progress progress-striped active">
												<div id="progressBarExportaPDCJ" class="progress-bar progress-bar-success" role="progressbar"  role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
													<span class="sr-only"></span>
												</div>
											</div>
										</div>
										
										<div class="col-md-2"></div>
									</div>
								</div>
							</div>
							<!-- END PROGRESS BAR-->
							
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