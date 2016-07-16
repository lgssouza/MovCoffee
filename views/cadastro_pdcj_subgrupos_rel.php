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
		$idsubgrupo = $_GET['id_subgrupo'];			
		
				
		if(!empty($idsubgrupo) && $idsubgrupo != "undefined")		 			
		{
					
			$status = "Alteração";			
			$sql 	= "SELECT * FROM `tb_pdcj_subgrupo_rel` a inner join `tb_pdcj_grupo_rel` b on a.fk_id_grupo = b.id_grupo WHERE id_subgrupo = " . $idsubgrupo;			
			
			$rs 	= $mysqli->query($sql);	
	
			if($rs)
			{
				$subgrupos = array();
        
				while($subgrupo = $rs->fetch_object())
				{
					array_push($subgrupos, $subgrupo);
				}
		
				$rs->close();
			}
		}
	}
	
	$idsubgrupo	= '';
	$descricao	= '';
	$idgrupo	= '';
	
	
	if(isset($subgrupos))
	{
		if(isset($subgrupos[0]->id_subgrupo))
		{
			$idsubgrupo = $subgrupos[0]->id_subgrupo;
		}
		
		if(isset($subgrupos[0]->subg_descricao))
		{
			$descricao = $subgrupos[0]->subg_descricao;
		}
		
		if(isset($subgrupos[0]->fk_id_grupo))
		{
			$idgrupo = $subgrupos[0]->fk_id_grupo;
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
					Cadastros
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_pdcj_subgrupos_rel.php">Sub-Grupos</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_pdcj_subgrupos_rel.php?idgrupo=<?php echo $idsubgrupo; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-th"></i>
								<?php echo $status; ?> de Sub-Grupo
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Informações do Sub-Grupo</h3>
									
								<div class="row">																	
									<div class="col-md-6">
										<div class="form-group">
											<input type="hidden" class="form-control" id="txtidsubgrupo" value="<?php echo $idsubgrupo; ?>">
											
											<label class="control-label">Descrição</label>	
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-list"></span>												 
												</span>
												<input type="text" class="form-control" id="txtdescricao" placeholder="Digite a descrição do sub-grupo" value="<?php echo $descricao; ?>">											
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">	
											<label class="control-label">Grupo</label>											
												<select class="form-control select2me" id="cmbgrupo" data-placeholder="Selecione...">											
												<?php 
												
													$sqlprod 	= "SELECT * FROM `tb_pdcj_grupo_rel`";	
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
															if(!empty($idgrupo) && $id_grupo != "undefined"){
																
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
									</div>
								</div>
																								
								<div id="alertcontas">
								<div>								
							</div>
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_pdcj_subgrupos_rel">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_pdcj_subgrupos_rel">
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