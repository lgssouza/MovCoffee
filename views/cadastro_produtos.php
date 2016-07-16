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
		
	if(isset($_GET['id_produto']))
	{
		$id = $_GET['id_produto'];				
				
		if(!empty($id) && $id != "undefined")		 			
		{
					
			$status = "Alteração";			
			$sql 	= "SELECT * FROM tb_produto WHERE id = " . $id;			
			
			$rs 	= $mysqli->query($sql);	
	
			if($rs)
			{
				$produtos = array();
        
				while($produto = $rs->fetch_object())
				{
					array_push($produtos, $produto);
				}
		
				$rs->close();
			}
		}
	}
	
	$id			= '';	
	$descricao 	= '';
		
	if(isset($produtos))
	{
		if(isset($produtos[0]->id))
		{
			$id = $produtos[0]->id;
		}
		
		if(isset($produtos[0]->descricao))
		{
			$descricao = $produtos[0]->descricao;
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
					Cadastros
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_produtos.php">Produtos</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_contas.php?id=<?php echo $id; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-money"></i>
								<?php echo $status; ?> de Produto
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<div class="row">																	
									<div class="col-md-12">
										<div class="form-group">
											<input type="hidden" class="form-control" id="txtidproduto" value="<?php echo $id; ?>">											
											<label class="control-label">Descrição</label>	
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-university"></span>													 
												</span>
												<input type="text" class="form-control" id="txtdescricao" placeholder="Digite a descrição do produto" value="<?php echo $descricao; ?>">											
											</div>
										</div>
									</div>
								</div>
																								
								<div id="alertprodutos">
								</div>								
							</div>
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_produtos">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_produtos">
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