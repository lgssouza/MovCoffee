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
		
	if(isset($_GET['id_grupo']))
	{
		$idgrupo = $_GET['id_grupo'];			
		
				
		if(!empty($idgrupo) && $idgrupo != "undefined")		 			
		{
					
			$status = "Alteração";			
			$sql 	= "SELECT * FROM `tb_pdcj_grupo_rel` WHERE id_grupo = " . $idgrupo;			
			
			$rs 	= $mysqli->query($sql);	
	
			if($rs)
			{
				$grupos = array();
        
				while($grupo = $rs->fetch_object())
				{
					array_push($grupos, $grupo);
				}
		
				$rs->close();
			}
		}
	}
	
	$idgrupo	= '';
	$descricao	= '';
	
	if(isset($grupos))
	{
		if(isset($grupos[0]->id_grupo))
		{
			$idgrupo = $grupos[0]->id_grupo;
		}
		
		if(isset($grupos[0]->grup_descricao))
		{
			$descricao = $grupos[0]->grup_descricao;
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
					<a href="consulta_pdcj_grupos_rel.php">Grupos</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_pdcj_grupos_rel.php?idgrupo=<?php echo $idgrupo; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-th-large"></i>
								<?php echo $status; ?> de Grupo
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Informações do Grupo</h3>
									
								<div class="row">																	
									<div class="col-md-12">
										<div class="form-group">
											<input type="hidden" class="form-control" id="txtidgrupo" value="<?php echo $idgrupo; ?>">
											
											<label class="control-label">Descrição</label>	
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-list"></span>												 
												</span>
												<input type="text" class="form-control" id="txtdescricao" placeholder="Digite a descrição do grupo" value="<?php echo $descricao; ?>">											
											</div>
										</div>
									</div>
								</div>
																								
								<div id="alertcontas">
								<div>								
							</div>
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_pdcj_grupos_rel">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_pdcj_grupos_rel">
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