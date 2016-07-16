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
	
	$sql	= "";
	$status = "Inclusão";
	
	if(isset($_GET['id_propriedade']))
	{
		$id_propriedade = $_GET['id_propriedade'];
	 	
		if(!empty($id_propriedade) && $id_propriedade != "undefined")
		{
			$status = "Alteração";
			
			$sql 	= "SELECT id_prop, prop_nome, prop_ie,
					   REPLACE(prop_areatotal,'.',',') prop_areatotal,
					   REPLACE(prop_areatotalcafe,'.',',') prop_areatotalcafe,
					   REPLACE(prop_previsaosacas,'.',',') prop_previsaosacas
					   FROM `tb_propriedade`
					   WHERE id_prop = " . $id_propriedade;
		}
	}
	
	$id_propriedade			= '';
	$nome_propriedade		= '';
	$ie_propriedade			= '';
	$areatotal				= '';
	$areatotalcafe			= '';
	$previsaosacas			= '';
	
	if(!empty($sql))
	{
		$rs = $mysqli->query($sql);
		
		if($rs)
		{
			$propriedade		= $rs->fetch_object();
			$id_propriedade 	= $propriedade->id_prop;
			$nome_propriedade	= $propriedade->prop_nome;
			$ie_propriedade		= $propriedade->prop_ie;
			$areatotal			= $propriedade->prop_areatotal;
			$areatotalcafe		= $propriedade->prop_areatotalcafe;
			$previsaosacas		= $propriedade->prop_previsaosacas;
			
			$rs->close();
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
					<a href="consulta_propriedade.php">Propriedades</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_propriedade.php?id_propriedade=<?php echo $id_propriedade; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-fort-awesome"></i>
								<?php echo $status; ?> de Propriedade
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->							
							<div class="form-body">
								<h3 class="form-section">Informações da Propriedade</h3>
								
								<div class="row">
									<div class="col-md-6">														
										<div class="form-group">
											<label class="control-label">Nome</label>
											
											<div class="input-group">
												<input type="hidden" class="form-control" id="txtidpropriedade" value="<?php echo $id_propriedade; ?>">
												
												<span class="input-group-addon">
													<span class="fa fa-home"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtnomepropriedade"  placeholder="Digite o nome da propriedade" value="<?php echo $nome_propriedade; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">IE</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-credit-card"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtiepropriedade" placeholder="Digite a inscrição estadual da propriedade" value="<?php echo $ie_propriedade; ?>">
											</div>											
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Área Total </label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-area-chart"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" id="txtareatotalpropriedade" placeholder="Digite a área total" value="<?php echo $areatotal; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Área de Café</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-object-group"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" id="txtareacafepropriedade" placeholder="Digite a área de café" value="<?php echo $areatotalcafe; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Previsão de Sacas</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-line-chart"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" id="txtprevisaosacaspropriedade" placeholder="Digite a previsão de sacas à ser colhido" value="<?php echo $previsaosacas; ?>">
											</div>											
										</div>
									</div>
								</div>
							</div>
								
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_propriedade">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_propriedade">
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