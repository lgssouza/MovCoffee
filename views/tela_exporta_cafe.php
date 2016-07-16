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
	
	$sql 			= "SELECT * FROM `tb_relatorio` WHERE rel_tipo = 2";
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
					Movimentação de Café
					<i class="fa fa-angle-right"></i>
				</li>
								
				<li>
					<a href="tela_exporta_cafe.php">Relatórios</a>
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
								Relatórios
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Filtros do Relatório</h3>									
								<div class="row">
									<div class="col-md-4">
										<label>Tipo</label>
										<div class="input-group">
											<div class="icheck-list">
												<label class="">
													<div  class="iradio_square-green" style="position: relative;">
														<input type="radio" name="tprel" id="tprel" value = "1"  class="icheck" data-radio="iradio_square-green" style="position: absolute; opacity: 0;">
														<ins id="divcafe1" class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>															
													</div> 
													Qualidade Bebida APAS 
												</label>
												<label class="">
													<div  class="iradio_square-green" style="position: relative;">
														<input type="radio" name="tprel" id="tprel2" value = "2" class="icheck" data-radio="iradio_square-green" style="position: absolute; opacity: 0;">
														<ins id="divcafe2" class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>															
													</div> 
													Qualidade Bebida Produtor 
												</label>
												<label class="">
													<div  class="iradio_square-green" style="position: relative;">
														<input type="radio" name="tprel" id="tprel" value = "3" data-valor = "3" class="icheck" data-radio="iradio_square-green" style="position: absolute; opacity: 0;">
														<ins id="divcafe3" class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>														
													</div> 
													Movimentação do Produtor
												</label>
											</div>
										</div>
									</div>
									<div id="divcomboprod" class="col-md-8" style="display:none">
									<div class="form-group">	
										<label class="control-label">Produtores</label>
										
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-users"></span>													 
											</span>
											
											<select class="form-control select2me" id="cmbfiltroprodutorescafe">	
												<option selected value="nenhum">Selecione um produtor...</option>	
																				
												<?php 
												
													$sql 			= "SELECT * FROM `tb_produtor`";
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
																echo "<option value=".$regs[$i]->id_prod.">".$regs[$i]->prod_nome."</option>";
														}
													}
																								
												?>
											</select>
										</div>
									</div>				
								</div>		
								</div>
							</div>
								
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="btn_exp_relatorio_cafe">
									<i class="fa fa-print"></i>
									&nbsp;Imprimir
								</button>
								
								<button type="button" class="btn red-thunderbird" id="btn_limpa_exp_relatorio_cafe">
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