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
		
	if(isset($_GET['id_recursos']))
	{
		$id_recursos = $_GET['id_recursos'];			
		
				
		if(!empty($id_recursos) && $id_recursos != "undefined")		 			
		{
					
			$status = "Alteração";			
			$sql 	= "select * from tb_pdcj_recursos a where a.id_recursos = $id_recursos order by a.recursos_data desc";			
			
			$rs 	= $mysqli->query($sql);	
	
			if($rs)
			{
				$recursos = array();
        
				while($recurso = $rs->fetch_object())
				{
					array_push($recursos, $recurso);
				}
		
				$rs->close();
			}
		}
	}
	
	$idrecurso 	= '';
	$descricao 	= '';
	$valor		= '';
	$qtd		= '';	
	$data 		= '';
	
	if(isset($recursos))
	{
		if(isset($recursos[0]->id_recursos))
		{
			$idrecurso = $recursos[0]->id_recursos;
		}
	
		if(isset($recursos[0]->recursos_descricao))
		{
			$descricao = $recursos[0]->recursos_descricao;			
		}
		
		if(isset($recursos[0]->recursos_valor))
		{
			$valor = 0;
			$valor =$recursos[0]->recursos_valor;
			
			if($valor!=NULL)
			{
				$valor = 'R$ '.number_format($valor, 2, ',', '.');
				$valor 	= str_replace('.','',$valor);
			}
			else
			{
				$valor = 'R$ '.number_format(0, 2, ',', '.');				
			}		
		}
		
		if(isset($recursos[0]->recursos_qtd))
		{
			$qtd = $recursos[0]->recursos_qtd;			
		}
		
				
		if(isset($recursos[0]->recursos_data))
		{
			$data = $recursos[0]->recursos_data;
			$data = date('d/m/Y', strtotime($data));
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
					<a href="consulta_lancamentos_recursos_plan.php">Recursos</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_lancamentos_recursos_plan.php?id_recursos=<?php echo $idrecurso; ?>"><?php echo $status; ?></a>
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
								<?php echo $status; ?> de Recurso
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Informações do Recurso</h3>
									
								<div class="row">			
									
									<input type="hidden" class="form-control" id="txtidrecursos" value="<?php echo $idrecurso; ?>">
									
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label">Descrição</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-crop"></span>													 
												</span>
												<input type="text" class="form-control" id="txtdescricao" placeholder="Digite a descrição do recurso" value="<?php echo $descricao; ?>">
											</div>
										</div>
									</div>
								</div>
									
								<div class="row">
									
									<div class="col-md-4">
										<label class="control-label">Valor</label>
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-money"></span>													 
											</span>
											<input type="text" class="form-control maskdinheiro" id="txtvalor" placeholder="Digite o valor do recurso" value="<?php echo $valor; ?>">
										</div>
									</div>		
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Quantidade</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-pagelines"></span>													 
												</span>
												<input type="number" class="form-control" id="txtqtd" placeholder="Digite a quantidade do recurso" value="<?php echo $qtd; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Data</label>											
											<div class="input-group date date-picker">
												<span class="input-group-addon">
													<span class="fa fa-calendar"></span>													 
												</span>
												<input type="text" class="form-control" id="txtdata" placeholder="Digite a data" value="<?php echo $data; ?>">
											</div>
										</div>
									</div>
								</div>
								
								<div id="alertcontas">
								<div>
								
							</div>
								
																																								
								
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_lancamentos_recursos_plan">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_lancamentos_recursos_plan">
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