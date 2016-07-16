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
    $rs 		= $mysqli->query('SELECT COUNT(*) FROM tb_categoria');
	
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
	
	$sql 	= "SELECT * FROM tb_categoria 
			   limit $inicial, $registros";
	$rs 	= $mysqli->query($sql);
	
	if($rs)
	{
		$categorias = array();
        
		while($categoria = $rs->fetch_object())
		{
			array_push($categorias, $categoria);
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
					<a href="consulta_categoria_usuario.php">Categorias de Usuário</a>
				</li>
			</ul>
			
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-fit-height green-jungle" onclick="cadastrocategoriausuario()">
						<i class="fa fa-plus"></i>
						Novo 						
					</button>		
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
							<i class="fa fa-tags"></i>Consulta de Categorias de Usuário
						</div>
						
						<div class="tools">
							<!-- <a href="javascript:;" class="collapse"></a>-->
						</div>
					</div>
					
					<div class="portlet-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="tbl_categorias">
								<thead>
									<tr>
										<th style="display: none">Código</th>
		          						<th>Descrição</th>
		          						
		          						<?php
						          			//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
						          			//{
						          				echo '<th style="text-align: center">Editar</th>';
											//}
										?>
						          		
						          		<?php
						          			//if(verificapermissaoexclusao("Categorias de Funcionário",$permissoes))
						          			//{
						          				echo '<th style="text-align: center">Excluir</th>';
											//}
										?>
																	
									</tr>
								</thead>
								
								<tbody>
									
									<?php
										      		
							  			for($i=0;$i<count($categorias);$i++)
										{
									        echo	'<tr>
										          		<td>'.$categorias[$i]->id_cat.'</td>
										          		<td>'.$categorias[$i]->cat_descricao.'</td>';
										          		
														//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-pencil" id="'.$categorias[$i]->id_cat.'" style="color:green;width:100%;height:100%"></span>
																		</a>
																	</td>';
														//}
														//
														//if(verificapermissaoexclusao("Categorias de Funcionário",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-trash" id="'.$categorias[$i]->id_cat.'" style="color:red;width:100%;height:100%"></span>
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
							include_once('funcoes/paginacao.php');
							
							/** Imprime Paginação **/
							echo paginacao_php(
												$pagina,					 /** Pagina atual **/
												$registros,					 /** Quantidade de registros por paginação  **/
												$totalpaginas,				 /** Total de paginas **/
												$totalregistros,			 /** Total de registros **/
												'consultacategoriasusuario', /** Nome da função javascrip arquivo 'funcoes.js' **/
												'categorias de usuário'		 /** Descrição que aparecera na paginação **/
											  );
							
						?>
						
					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->
				
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		
	</div>
</div>
<!-- END CONTENT -->

<?php

	$mysqli->close();
	
?>