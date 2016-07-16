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
	
	$status = "Inclusão";
	
	if(isset($_GET['id_categoria']))
	{
		$id_categoria = $_GET['id_categoria'];
				
		if(empty($id_categoria) || $id_categoria == "undefined")
		{
			$sql 	= "SELECT * FROM `tb_formulario`";
			$sql2 	= "SELECT * FROM `tb_relatorio`";
		}
		else 
		{
			$status = "Alteração";
			
			$sql 	= "SELECT * FROM `tb_permissao_formulario` 
					   INNER JOIN `tb_categoria` ON `fk_id_cat` = `id_cat` 
					   INNER JOIN `tb_formulario` ON `fk_id_form` = `id_form`
					   WHERE id_cat = " . $id_categoria;
			
			$sql2 	= "SELECT * FROM `tb_permissao_relatorio` 
					   INNER JOIN `tb_categoria` ON `fk_id_cat` = `id_cat` 
					   INNER JOIN `tb_relatorio` ON `fk_id_rel` = `id_rel`
					   WHERE id_cat = " . $id_categoria;
		}
	}
	else
	{
		$sql 	= "SELECT * FROM `tb_formulario`";
		$sql2 	= "SELECT * FROM `tb_relatorio`";
	}
	
	$rs 	= $mysqli->query($sql);
	$rs2 	= $mysqli->query($sql2);
	
	if($rs)
	{
		$formularios = array();
        
		while($formulario = $rs->fetch_object())
		{
			array_push($formularios, $formulario);
		}
		
		$rs->close();
	}
	
	if($rs2)
	{
		$relatorios = array();
        
		while($relatorio = $rs2->fetch_object())
		{
			array_push($relatorios, $relatorio);
		}
		
		$rs2->close();
	}
	
	$id_categoria 		= '';
	$decricao_categoria = '';
	
	if(isset($formularios[0]->id_cat))
	{
		$id_categoria 		= $formularios[0]->id_cat;
	}
	
	if(isset($formularios[0]->cat_descricao))
	{
		$decricao_categoria = $formularios[0]->cat_descricao;
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
					Cadastros
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_categoria_usuario.php">Categorias de Usuário</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_categoria_usuario.php?id_categoria=<?php echo $id_categoria; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-tags"></i>
								<?php echo $status; ?> de Categoria de Usuário
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Informações da Categoria</h3>
									
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label">Descrição da Categoria</label>
																								
											<div class="input-group">
												<input type="hidden" class="form-control" id="txtidcategoria" value="<?php echo $id_categoria; ?>">
												
												<span class="input-group-addon">
													<span class="fa fa-list"></span> 
												</span>
																							  
												<input type="text" class="form-control" id="txtdescricaocategoria" placeholder="Digite a descrição da categoria" value="<?php echo $decricao_categoria; ?>">
											</div>
										</div>
									</div>
								</div>							
						
								<h3 class="form-section">Permissões de Formulários da Categoria</h3>
								
								<div class="row">
									<div class="col-md-12">
										<table class="table table-striped table-hover" id="tbl_permissoes_formularios">
									    	<thead>
									     		<tr>
									          		<th>Código</th>
									          		<th>Formulário</th>
									          		<th style="text-align: center">Leitura</th>
									          		<th style="text-align: center">Gravação</th>
									          		<th style="text-align: center">Edição</th>
									          		<th style="text-align: center">Exclusão</th>
									        	</tr>
									      	</thead>
									     
									      	<tbody>
									      		<?php
									      		
									      			for($i=0;$i<count($formularios);$i++)
													{
												        echo	'<tr>
													          		<td>'.$formularios[$i]->id_form.'</td>
													          		<td>'.$formularios[$i]->form_descricao.'</td>';
																
														if(isset($formularios[$i]->perm_visualizar))
														{			
													     	if($formularios[$i]->perm_visualizar == 1)
															{
																echo	'<td align="center"><a><span class="glyphicon glyphicon-ok" style="color:green;width:100%;height:100%"></span></a></td>';	
															}
															else 
															{
																echo	'<td align="center"><a><span class="glyphicon glyphicon-remove" style="color:red;width:100%;height:100%"></span></a></td>';	
															}
														}
														else
														{
															echo	'<td align="center"><a><span class="glyphicon glyphicon-remove" style="color:red;width:100%;height:100%"></span></a></td>';
														}
														
														if(isset($formularios[$i]->perm_incluir))
														{			
													     	if($formularios[$i]->perm_incluir == 1)
															{
																echo	'<td align="center"><a><span class="glyphicon glyphicon-ok" style="color:green;width:100%;height:100%"></span></a></td>';	
															}
															else 
															{
																echo	'<td align="center"><a><span class="glyphicon glyphicon-remove" style="color:red;width:100%;height:100%"></span></a></td>';	
															}
														}
														else
														{
															echo	'<td align="center"><a><span class="glyphicon glyphicon-remove" style="color:red;width:100%;height:100%"></span></a></td>';
														}

														if(isset($formularios[$i]->perm_alterar))
														{			
													     	if($formularios[$i]->perm_alterar == 1)
															{
																echo	'<td align="center"><a><span class="glyphicon glyphicon-ok" style="color:green;width:100%;height:100%"></span></a></td>';	
															}
															else 
															{
																echo	'<td align="center"><a><span class="glyphicon glyphicon-remove" style="color:red;width:100%;height:100%"></span></a></td>';	
															}
														}
														else
														{
															echo	'<td align="center"><a><span class="glyphicon glyphicon-remove" style="color:red;width:100%;height:100%"></span></a></td>';
														}
														
														if(isset($formularios[$i]->perm_excluir))
														{
															if($formularios[$i]->perm_excluir == 1)
															{
																echo	'<td align="center"><a><span class="glyphicon glyphicon-ok" style="color:green;width:100%;height:100%"></span></a></td>';	
															}
															else 
															{
																echo	'<td align="center"><a><span class="glyphicon glyphicon-remove" style="color:red;width:100%;height:100%"></span></a></td>';	
															}
														}
														else
														{
															echo	'<td align="center"><a><span class="glyphicon glyphicon-remove" style="color:red;width:100%;height:100%"></span></a></td>';
														}
										
													    echo 	'</tr>';
													}
													
									        	?>
									    	</tbody>
										</table>
									</div>
								</div>
								
								<h3 class="form-section">Permissões de Relatórios da Categoria</h3>
					
								<div class="row">
									<div class="col-md-12">
										<table class="table table-striped table-hover" id="tbl_permissoes_relatorios">
									    	<thead>
									     		<tr>
									          		<th>Código</th>
									          		<th>Relatório</th>
									          		<th style="text-align: center">Leitura</th>
									        	</tr>
									      	</thead>
									     
									      	<tbody>
									      		<?php
									      		
									      			for($i=0;$i<count($relatorios);$i++)
													{
												        echo	'<tr>
													          		<td>'.$relatorios[$i]->id_rel.'</td>
													          		<td>'.$relatorios[$i]->rel_descricao.'</td>';
																
														if(isset($relatorios[$i]->perm_visualizar))
														{			
													     	if($relatorios[$i]->perm_visualizar == 1)
															{
																echo	'<td align="center"><a><span class="glyphicon glyphicon-ok" style="color:green;width:100%;height:100%"></span></a></td>';	
															}
															else 
															{
																echo	'<td align="center"><a><span class="glyphicon glyphicon-remove" style="color:red;width:100%;height:100%"></span></a></td>';	
															}
														}
														else
														{
															echo	'<td align="center"><a><span class="glyphicon glyphicon-remove" style="color:red;width:100%;height:100%"></span></a></td>';
														}
																					
													    echo 	'</tr>';
													}
													
									        	?>
									    	</tbody>
										</table>
									</div>
								</div>
							</div>
								
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_categoria_usuario">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_categoria_usuario">
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