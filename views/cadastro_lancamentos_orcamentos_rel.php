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
		
	if(isset($_GET['id_subgrupo_valores']))
	{
		$id_subgrupo_valores = $_GET['id_subgrupo_valores'];			
		
				
		if(!empty($id_subgrupo_valores) && $id_subgrupo_valores != "undefined")		 			
		{
					
			$status = "Alteração";			
			$sql 	= "SELECT a.id_subgrupo, b.id_grupo, c.id_subgrupo_valores, c.subg_avaliacao, 
				c.subg_premio_orcado, c.subg_outros_orcado, c.subg_ano 
				FROM tb_pdcj_subgrupo_rel a 
				inner join tb_pdcj_grupo_rel b on a.fk_id_grupo = b.id_grupo 
				inner join tb_pdcj_subgrupo_rel_valores c on a.id_subgrupo = c.fk_id_subg 
				WHERE id_subgrupo_valores = " . $id_subgrupo_valores;			
			
			$rs 	= $mysqli->query($sql);	
	
			if($rs)
			{
				$subgrupos_valores = array();
        
				while($subgrupo_valores = $rs->fetch_object())
				{
					array_push($subgrupos_valores, $subgrupo_valores);
				}
		
				$rs->close();
			}
		}
	}
	
	$idsubgvalores	= '';
	$idgrupo		= '';
	$idsubgrupo		= '';
	$premio_orcado 	= '';
	$outros_orcado 	= '';
	$ano 			= '';
	$avaliacao		= '';
	
	
	if(isset($subgrupos_valores))
	{
		if(isset($subgrupos_valores[0]->id_subgrupo_valores))
		{
			$idsubgvalores = $subgrupos_valores[0]->id_subgrupo_valores;
		}
		
		if(isset($subgrupos_valores[0]->id_subgrupo))
		{
			$idsubgrupo = $subgrupos_valores[0]->id_subgrupo;
		}
		
		if(isset($subgrupos_valores[0]->subg_avaliacao))
		{
			$avaliacao = $subgrupos_valores[0]->subg_avaliacao;			
		}
		
		if(isset($subgrupos_valores[0]->id_grupo))
		{
			$idgrupo = $subgrupos_valores[0]->id_grupo;
		}			
		
		if(isset($subgrupos_valores[0]->subg_premio_orcado))
		{
			$premio_orcado = 0;
			$premio_orcado = $subgrupos_valores[0]->subg_premio_orcado;
			
			if($premio_orcado!=NULL)
			{
				$premio_orcado = 'R$ '.number_format($premio_orcado, 2, ',', '.');
				$premio_orcado 	= str_replace('.','',$premio_orcado);
			}
			else
			{
				$premio_orcado = 'R$ '.number_format(0, 2, ',', '.');				
			}
		}
		
		if(isset($subgrupos_valores[0]->subg_outros_orcado))
		{
			$outros_orcado = 0;
			$outros_orcado = $subgrupos_valores[0]->subg_outros_orcado;
			
			if($outros_orcado!=NULL)
			{
				$outros_orcado = 'R$ '.number_format($outros_orcado, 2, ',', '.');
				$outros_orcado 	= str_replace('.','',$outros_orcado);
			}
			else
			{
				$outros_orcado = 'R$ '.number_format(0, 2, ',', '.');				
			}
		}
		
		if(isset($subgrupos_valores[0]->subg_ano))
		{
			$ano = $subgrupos_valores[0]->subg_ano;
			
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
					<a href="consulta_lancamentos_orcamentos_rel.php">Orçamentos</a>
					<i class="fa fa-angle-right"></i>
					
				</li>
				
				<li>
					<a href="cadastro_lancamentos_orcamentos_rel.php?id_subgrupo_valores=<?php echo $idsubgvalores; ?>"><?php echo $status; ?></a>
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
									
									<input type="hidden" class="form-control" id="txtidsubgvalores" value="<?php echo $idsubgvalores; ?>">
																							
									<div class="col-md-6">
										<label class="control-label">Grupo</label>
										<select class="form-control select2me" id="cmbgrupolanc" data-param="<?php echo $idsubgrupo; ?>" data-placeholder="Selecione...">											
														
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
												
									<div class="col-md-6">
										<label class="control-label">Sub-Grupo</label>	
														
										<select class="form-control select2me" id="cmbsubgrupolanc" name="cmbsubgrupolanc" data-placeholder="Selecione...">	
											<option value=""> Selecione um Sub-Grupo...</option>
										</select>																						
									</div>
								</div>
									
								<!-- Nesse formulário tem o BR para o espaço porque o select está ficando errado
									quando criamos ele com input-group, o icone faz a seleção bugar.  ai coloquei
									o br para dar espaço entre os campos. -->
										
								<br />
									
								<div class="row">
									
									<div class="col-md-4">
										<label class="control-label">Prêmio Orçado</label>
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-money"></span>													 
											</span>
											<input type="text" class="form-control maskdinheiro" id="txtpremio" placeholder="Digite o valor" value="<?php echo $premio_orcado; ?>">
										</div>
									</div>
										
									<div class="col-md-4">
										<label class="control-label">Outros Recursos Orçado</label>
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-money"></span>													 
											</span>
											<input type="text" class="form-control maskdinheiro" id="txtoutros" placeholder="Digite o valor" value="<?php echo $outros_orcado; ?>">
										</div>
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
											<label class="control-label">Avaliação</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-list"></span>													 
												</span>											
												<textarea class="form-control" maxlength="250" rows="5" id="txtavaliacao" placeholder="Digite a Avaliação"  style="margin: 0px -6.75px 0px 0px;"><?php echo $avaliacao ?></textarea>
											</div>																							
										</div>
									</div>
									
								</div>
								
								<div id="alertcontas">
								<div>
								
							</div>
								
																																								
								
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_lancamentos_orcamentos_rel">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_lancamentos_orcamentos_rel">
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