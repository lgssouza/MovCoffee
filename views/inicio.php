<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');

?>

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
		
		<!-- BEGIN PAGE HEADER-->
		<h3 class="page-title">
			Painel de Informações
		</h3>
		
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="inicio.php">Início</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="#">Painel de Informações</a>
				</li>
			</ul>
			<!--
			<div class="page-toolbar">
				<div id="dashboard-report-range" class="tooltips btn btn-fit-height btn-sm green-jungle" btn-dashboard-daterange" data-container="body" data-placement="left" data-original-title="Change dashboard date range">
					<i class="icon-calendar"></i>
					&nbsp;&nbsp; 
					<i class="fa fa-angle-down"></i>
				</div>
			</div>
			-->
		</div>
		<!-- END PAGE HEADER-->
		
		<!-- BEGIN DASHBOARD STATS -->
		<div class="row">
			<!--<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="dashboard-stat dashboard-stat-light grey-cascade" href="javascript:;">
					<div class="visual">
						<i class="fa fa-comments"></i>
					</div>
					
					<div class="details">
						<div class="number">
							0
						</div>
						
						<div class="desc">
							Informação Rapidas
						</div>
					</div>
				</a>
			</div>
			
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="dashboard-stat dashboard-stat-light grey-cascade" href="javascript:;">
					<div class="visual">
						<i class="fa fa-trophy"></i>
					</div>
					
					<div class="details">
						<div class="number">
							 R$ 0,00
						</div>
						
						<div class="desc">
							Informação Rapidas
						</div>
					</div>
				</a>
			</div>
			
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="dashboard-stat dashboard-stat-light grey-cascade" href="javascript:;">
					<div class="visual">
						<i class="fa fa-shopping-cart"></i>
					</div>
					<div class="details">
						<div class="number">
							 0
						</div>
						
						<div class="desc">
							Informação Rapidas
						</div>
					</div>
				</a>
			</div>-->
			
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">					
				<?php			
					//Café Bolsa de NY		
					//<!-- Widgets Notícias Agrícolas - www.noticiasagricolas.com.br/widgets -->
					echo "<script type=\"text/javascript\" src=\"http://www.noticiasagricolas.com.br/widget/cotacoes.js.php?id=4&fonte=Arial%2C%20Helvetica%2C%20sans-serif&tamanho=10pt&largura=300px&cortexto=333333&corcabecalho=B2C3C6&corlinha=DCE7E9&imagem=true&output=js\"></script>";					
				?>
			</div>
			
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">					
				<?php			
					//Dolar		
					//<!-- Widgets Notícias Agrícolas - www.noticiasagricolas.com.br/widgets -->
					echo "<script type=\"text/javascript\" src=\"http://www.noticiasagricolas.com.br/widget/cotacoes.js.php?id=17&fonte=Arial%2C%20Helvetica%2C%20sans-serif&tamanho=10pt&largura=300px&cortexto=333333&corcabecalho=B2C3C6&corlinha=DCE7E9&imagem=true&output=js\"></script>";					
				?>
			</div>
			
			<!-- Widgets Notícias Agrícolas - www.noticiasagricolas.com.br/widgets -->

			
		</div>	
		<div class="row">		
			
			
		</div>		
		<!-- END DASHBOARD STATS -->
		
		<div class="clearfix">
		</div>
	</div>
	<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTENT -->