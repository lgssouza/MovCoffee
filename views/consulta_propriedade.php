<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
	
	//if(!verificapermissao("Funcionários",$permissoes))
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
	
	/** INICIO FILTROS **/
	$filtros = " WHERE 1 = 1 ";
	
	if(isset($_GET["propriedade"]))
	{
		if(!empty($_GET["propriedade"]) && $_GET["propriedade"] != "undefined" && $_GET["propriedade"] != "nenhum")
		{
			$filtros = $filtros . " AND id_prop = " . $_GET["propriedade"] . " ";
		}
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
	
    $inicial 	= ($pagina-1) * $registros;
    $rs        	= $mysqli->query('SELECT COUNT(*) FROM tb_propriedade'.$filtros);
	
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
	
	$sql 	= "SELECT id_prop, prop_nome, prop_ie,
			   REPLACE(prop_areatotal,'.',',') prop_areatotal,
			   REPLACE(prop_areatotalcafe,'.',',') prop_areatotalcafe,
			   REPLACE(prop_previsaosacas,'.',',') prop_previsaosacas
			   FROM tb_propriedade $filtros
			   limit $inicial, $registros";
	
	$rs     = $mysqli->query($sql);
	
	if($rs)
	{
		$propriedades = array();
        
		while($propriedade = $rs->fetch_object())
		{
			array_push($propriedades, $propriedade);
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
					Cadastros
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_propriedade.php">Propriedades</a>
				</li>
			</ul>
			
			<div class="page-toolbar">
				<div class="btn-toolbar">
					<div class="btn-group pull-right">
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="relatorio_propriedade">
							<i class="fa fa-print"></i>
							&nbsp;Relatório 						
						</button>
					</div>
					
					<div class="btn-group pull-right">	
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_filtros_propriedade">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar 						
						</button>
					</div>
					
					<div class="btn-group pull-right">
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" onclick="cadastropropriedade()">							
							<i class="fa fa-plus"></i>							
							&nbsp;Novo 						
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
							<i class="fa fa-fort-awesome"></i>Consulta de Propriedades
						</div>
						
						<div class="tools">
							<!-- <a href="javascript:;" class="collapse"></a>-->
						</div>
					</div>
					
					<div class="portlet-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="tbl_propriedade">
								<thead>
									<tr>
										<th style="display: none">Código</th>
						          		<th>Nome</th>
						          		<th>IE</th>
						          		<th>Área de Café</th>
						          		<th>Área Total</th>
						          		<th>Prev.Sacas</th>
						          								          		
						          		<?php
						          			//if(verificapermissaoedicao("Funcionários",$permissoes))
						          			//{
						          				echo '<th style="text-align: center">Editar</th>';
											//}
										?>
						          		
						          		<?php
						          			//if(verificapermissaoexclusao("Funcionários",$permissoes))
						          			//{
						          				echo '<th style="text-align: center">Excluir</th>';
											//}
										?>																	
									</tr>
								</thead>
								
								<tbody>									
									<?php
										   		
						      			for($i=0;$i<count($propriedades);$i++)
										{
									        echo	'<tr>
										          		<td style="display: none">'.$propriedades[$i]->id_prop.'</td>
										          		<td>'.$propriedades[$i]->prop_nome.'</td>
														<td>'.$propriedades[$i]->prop_ie.'</td>
														<td>'.$propriedades[$i]->prop_areatotalcafe.'</td>
														<td>'.$propriedades[$i]->prop_areatotal.'</td>
														<td>'.$propriedades[$i]->prop_previsaosacas.'</td>';
										          												          		
														//if(verificapermissaoedicao("Funcionários",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-pencil" id="'.$propriedades[$i]->id_prop.'" style="color:green;width:100%;height:100%"></span>
																		</a>
																	</td>';
														//}
														
														//if(verificapermissaoexclusao("Funcionários",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-trash" id="'.$propriedades[$i]->id_prop.'" style="color:red;width:100%;height:100%"></span>
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
												'consultapropriedade',		 /** Nome da função javascrip arquivo 'funcoes.js' **/
												'propriedades'				 /** Descrição que aparecera na paginação **/
											  );
							
						?>
						
					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->
				
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		<!-- INICIO FIM MODAL DE CONSULTA -->
		<div id="modal_filtros_propriedade" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Filtros para Consulta de Propriedades
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">										
								<div class="col-md-12">
									<div class="form-group">	
										<label class="control-label">Propriedades</label>
										
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-fort-awesome"></span>													 
											</span>
											
											<select class="form-control select2me" id="cmbfiltropropriedade">	
												<option selected value="nenhum">Selecione uma propriedade...</option>	
																				
												<?php 
												
													$sql 			= "SELECT * FROM `tb_propriedade`";
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
																echo "<option value=".$regs[$i]->id_prop.">".$regs[$i]->prop_nome."</option>";
														}
													}
																								
												?>
											</select>
										</div>
									</div>				
								</div>
							</div>		
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_pesquisa_propriedade">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_pesquisa_propriedade">
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