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
		
	if(isset($_GET['id_grupo_valores']))
	{
		$id_grupo_valores = $_GET['id_grupo_valores'];			
		
				
		if(!empty($id_grupo_valores) && $id_grupo_valores != "undefined")		 			
		{
					
			$status = "Alteração";			
			$sql 	= "select * from tb_pdcj_grupo_plan a inner join tb_pdcj_grupo_plan_valores b on a.id_grupo = b.fk_id_grupo where b.id_grupo_valores = " . $id_grupo_valores;			
			
			$rs 	= $mysqli->query($sql);	
	
			if($rs)
			{
				$grupos_valores = array();
        
				while($grupo_valores = $rs->fetch_object())
				{
					array_push($grupos_valores, $grupo_valores);
				}
		
				$rs->close();
			}
		}
	}
	
	$idgrupvalores	= '';
	$idgrupo		= '';
	$objetivo		= '';
	$ano 			= '';
	
	
	
	if(isset($grupos_valores))
	{
		if(isset($grupos_valores[0]->id_grupo_valores))
		{
			$idgrupvalores = $grupos_valores[0]->id_grupo_valores;
		}
	
		if(isset($grupos_valores[0]->grup_objetivo))
		{
			$objetivo = $grupos_valores[0]->grup_objetivo;			
		}
		
		if(isset($grupos_valores[0]->id_grupo))
		{
			$idgrupo = $grupos_valores[0]->id_grupo;
		}			
				
		if(isset($grupos_valores[0]->grup_ano))
		{
			$ano = $grupos_valores[0]->grup_ano;
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
					<a href="consulta_lancamentos_orcamentos_plan.php">Orçamentos</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_lancamentos_orcamentos_plan.php?id_grupo_valores=<?php echo $idgrupvalores; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-opera"></i>
								<?php echo $status; ?> de Orçamentos
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Informações do Orçamento</h3>
									
								<div class="row">			
									
									<input type="hidden" class="form-control" id="txtidgrupvalores" value="<?php echo $idgrupvalores; ?>">
																							
									<div class="col-md-8">
										<label class="control-label">Grupo</label>
										<select class="form-control select2me" id="cmbgrupolancplan" data-placeholder="Selecione...">											
														
										<?php 
										
											$sqlprod 	= "SELECT * FROM `tb_pdcj_grupo_plan`";	
											$rsgrupo 	= $mysqli->query($sqlprod);	
									
											if($rsgrupo)
											{
												$regsgrupo = array();
								        
												while($reggrupo = $rsgrupo->fetch_object())
												{
													array_push($regsgrupo, $reggrupo);
													
												}
										
												$rsgrupo->close();
											}
											
											
											echo "<option selected value =\"\">Selecione um Grupo...</option>";
											for($i=0;$i<count($regsgrupo);$i++)
											{
												
												if(isset($idgrupo))
												{
													if(!empty($idgrupo) && $idgrupo != "undefined"){
														
														if($idgrupo==$regsgrupo[$i]->id_grupo)
														{																	
															echo "<option value=".$regsgrupo[$i]->id_grupo." selected>".$regsgrupo[$i]->grup_descricao."</option>";
														}
														else
														{
															echo "<option value=".$regsgrupo[$i]->id_grupo.">".$regsgrupo[$i]->grup_descricao."</option>";
														}
													}
													else
													{
														echo "<option value=".$regsgrupo[$i]->id_grupo.">".$regsgrupo[$i]->grup_descricao."</option>";
													}
												}
												else
												{
													echo "<option value=".$regsgrupo[$i]->id_grupo.">".$regsgrupo[$i]->grup_descricao."</option>";	
												}
												
												 
											}
											
										?>
										</select>												
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Ano PDCJ</label>											
											<div class="input-group date date-pickerano">
												<span class="input-group-addon">
													<span class="fa fa-calendar"></span>													 
												</span>
												<input type="text" class="form-control" id="txtano" placeholder="Digite o Ano" value="<?php echo $ano; ?>">
											</div>
										</div>
									</div>
								</div>
									
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label">Objetivo</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-list"></span>													 
												</span>											
												<input type="text" class="form-control" id="txtobjetivo" placeholder="Digite o Objetivo" value="<?php echo $objetivo; ?>">
											</div>																							
										</div>
									</div>
								</div>
								
								<div id="alertcontas">
								<div>
								
							</div>
								
																																								
								
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_lancamentos_orcamentos_plan">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_lancamentos_orcamentos_plan">
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