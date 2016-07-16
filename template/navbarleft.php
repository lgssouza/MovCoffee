<?php

	if(!isset($menu1))
	{
		$menu1 = '';			
	}
	
	if(!isset($menu2))
	{
		$menu2 = '';
	}
	
	if(!isset($menu3))
	{
		$menu3 = '';
	}
	
	if(!isset($menu4))
	{
		$menu4 = '';
	}
	
?>
<!-- BEGIN CONTAINER -->
<div class="container">
	<div class="page-container">
		
		<!-- BEGIN SIDEBAR -->
		<div class="page-sidebar-wrapper">
			<div class="page-sidebar navbar-collapse collapse">
				
				<!-- BEGIN SIDEBAR MENU -->
				<ul class="page-sidebar-menu page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
					
					<?php 
						echo retorna_menu_active($menu1,'Início');
					?>
					
						<a href="inicio.php">
							<i class="icon-home"></i>
							<span class="title">Início</span>
							
							<?php 
								echo retorna_spam_selected($menu1,'Início');
							?>
							
						</a>
					</li>
					
					<?php 
						echo retorna_menu_active($menu1,'Cadastros');
					?>
					
						<a href="">
							<i class="icon-folder"></i>
							<span class="title">Cadastros</span>
							
							<?php 
								echo retorna_spam_selected($menu1,'Cadastros');
							?>
							
						</a>
						
						<ul class="sub-menu">
							
							<?php 
								//echo retorna_submenu_active2($menu2,'Categorias de Usuário');
							?>
							<!--
								<a href="consulta_categoria_usuario.php">
									<i class="fa fa-tags"></i>
									Categorias de Usuário
								</a>
							</li>-->
							
							<?php 
								//echo retorna_submenu_active2($menu2,'Clientes');
							?>
							<!--
								<a href="">
									<i class="fa fa-male"></i>
									Clientes
								</a>
							</li>-->
							
							<?php 
								echo retorna_submenu_active2($menu2,'Contas');
							?>
					
								<a href="consulta_contas.php">
									<i class="fa fa-money"></i>
									Contas Bancárias
								</a>
							</li>
							
							<?php 
								echo retorna_submenu_active2($menu2,'Funcionários');
							?>
							
								<a href="consulta_funcionario.php">
									<i class="fa fa-user"></i>
									Funcionários
								</a>
							</li>
							
							<?php 
								echo retorna_submenu_active2($menu2,'Produtores');
							?>
							
								<a href="consulta_produtor.php">
									<i class="fa fa-users"></i>
									Produtores
								</a>
							</li>
							
							<?php 
								echo retorna_submenu_active2($menu2,'Clientes');
							?>
							
								<a href="consulta_clientes.php">
									<i class="fa fa-users"></i>
									Clientes
								</a>
							</li>
							
							<?php 
								echo retorna_submenu_active2($menu2,'Fornecedores');
							?>
							
								<a href="consulta_fornecedores.php">
									<i class="fa fa-users"></i>
									Fornecedores
								</a>
							</li>
							
							<?php 
								echo retorna_submenu_active2($menu2,'Estoque');
							?>
							
								<a href="">
									<i class="fa fa-archive"></i>
									Estoque
								</a>
								
								<ul class="sub-menu">
											
										<?php 
											echo retorna_submenu_active4($menu3,'Produtos');
										?>
										
											<a href="consulta_produtos.php">
												<i class="fa fa-archive"></i>
												Produtos
											</a>
										</li>
										
										<?php 
											echo retorna_submenu_active4($menu3,'Entrada e Saída');
										?>
										
											<a href="consulta_estoque.php">
												<i class="fa fa-exchange"></i>
												Entrada e Saída
											</a>
										</li>										
									</ul>
							</li>
							
							<?php 
								echo retorna_submenu_active2($menu2,'Safra');
							?>
							
								<a href="consulta_safras.php">
									<i class="fa fa-leaf"></i>
									Safra
								</a>
							</li>							
							
							<?php 
								echo retorna_submenu_active2($menu2,'Propriedades');
							?>
							
								<a href="consulta_propriedade.php">
									<i class="fa fa-fort-awesome"></i>
									Propriedades
								</a>
							</li>
						</ul>
					</li>
					
					<?php 
						echo retorna_menu_active($menu1,'Financeiro');
					?>
					
						<a href="">
							<i class="fa fa-usd"></i>
							<span class="title">Financeiro</span>
							
							<?php 
								echo retorna_spam_selected($menu1,'Financeiro'); 
							?>		
												
						</a>
						
						<ul class="sub-menu">
													
							<?php 
								echo retorna_submenu_active2($menu2,'Movimentação');
							?>
					
								<a href="consulta_movimentacao_contas.php">
									<i class="fa fa-credit-card"></i>
									Movimentação
								</a>
							</li>
																			
							<?php 
								echo retorna_submenu_active2($menu2,'Contas a Receber');
							?>
					
								<a href="consulta_conta_receber.php">
									<i class="fa fa-credit-card"></i>
									Contas a Receber
								</a>
							</li>
																			
							<?php 
								echo retorna_submenu_active2($menu2,'Contas a Pagar');
							?>
					
								<a href="consulta_conta_pagar.php">
									<i class="fa fa-credit-card"></i>
									Contas a Pagar
								</a>
							</li>
						</ul>
					</li>
					
					<?php 
						echo retorna_menu_active($menu1,'Movimentação de Café');
					?>
					
						<a href="">
							<i class="fa fa-exchange "></i>
							<span class="title">Movimentação de Café</span>						
							
							<?php 
								echo retorna_spam_selected($menu1,'Movimentação de Café');
							?>
							
						</a>
						
						<ul class="sub-menu">
							
							<?php 
								echo retorna_submenu_active2($menu2,'Entradas');
							?>
							
								<a href="consulta_entrada_cafe.php">									
									<i class="fa fa-cart-plus"></i>
									Entradas
									<span class="arrow"></span>
								</a>
							</li>
							
							<?php 
								echo retorna_submenu_active2($menu2,'Vendas');
							?>
							
								<a href="consulta_venda_cafe.php">									
									<i class="fa fa-cart-arrow-down" style="transform: rotateY(180deg);"></i>
									Vendas
									<span class="arrow"></span>
								</a>
							</li>
							<?php 
								echo retorna_submenu_active2($menu2,'Relatórios');
							?>
							
								<a href="tela_exporta_cafe.php">									
									<i class="fa fa-print"></i>
									Relatórios
									<span class="arrow"></span>
								</a>
							</li>
						</ul>
					</li>
					
					<?php 
						echo retorna_menu_active($menu1,'PDCJ');
					?>
					
						<a href="">
							<i class="fa fa-tasks"></i>
							<span class="title">PDCJ</span>						
							
							<?php 
								echo retorna_spam_selected($menu1,'PDCJ');
							?>
							
						</a>	
										
						<ul class="sub-menu">
							
							<?php 
								echo retorna_submenu_active2($menu2,'Cadastros');
							?>
							
								<a>									
									<i class="icon-folder"></i>
									Cadastros
									<span class="arrow"></span>
								</a>
							
								<ul class="sub-menu">
											
										<?php 
											echo retorna_submenu_active4($menu3,'Grupos');
										?>
										
											<a href="consulta_pdcj_grupos_rel.php">
												<i class="fa fa-th-large"></i>
												Grupos
											</a>
										</li>
										
										<?php 
											echo retorna_submenu_active4($menu3,'SubGrupos');
										?>
										
											<a href="consulta_pdcj_subgrupos_rel.php">
												<i class="fa fa-th"></i>
												Sub-Grupos
											</a>
										</li>										
									</ul>
								</li>
									
							<?php 
								echo retorna_submenu_active2($menu2,'Lançamentos');
							?>
							
								<a>									
									<i class="fa fa-arrow-right"></i>
									Lançamentos
									<span class="arrow"></span>
								</a>
							
								<ul class="sub-menu">	
									
									<?php 
										echo retorna_submenu_active3($menu3,'Recursos');
									?>
									
										<a href="consulta_lancamentos_recursos_plan.php">
											<i class="fa fa-dollar"></i>
											Recursos
										</a>
									</li>			
												
									<?php 
										echo retorna_submenu_active3($menu3,'Orçamentos');
									?>
									
										<a href="consulta_lancamentos_orcamentos_rel.php">
											<i class="fa fa-opera"></i>
											Orçamentos
										</a>
									</li>	
																			
									<?php 
										echo retorna_submenu_active3($menu3,'Gastos');
									?>
									
										<a href="consulta_lancamentos_gastos_rel.php">
											<i class="fa fa-dollar"></i>
											Gastos
										</a>
									</li>
								</ul>
								
							<?php 
								echo retorna_submenu_active2($menu2,'Exportação');
							?>							
								<a href="tela_exporta_pdcj.php">						
									<i class="fa fa-print"></i>
									Exportação									
								</a>								
							</li>
						</ul>							
					</li>
				
					<li>					
						<a href="getLogout.php">
							<i class="fa fa-sign-out"></i>
							<span class="title">Sair</span>
						</a>
					</li>
				</ul>
				<!-- END SIDEBAR MENU -->
				
			</div>
		</div>
		<!-- END SIDEBAR -->
		
<?php 

	function retorna_menu_active($menu1,$menu)
	{
		if($menu1 == $menu)
		{
			return '<li class="start active">';
		}
		else
		{
			return '<li>';
		}			
	}
	
	function retorna_submenu_active2($menu2,$submenu)
	{
		if($menu2 == $submenu)
		{
			return '<li class="start active">';
		}
		else
		{
			return '<li>';
		}			
	}
	
	function retorna_submenu_active3($menu3,$submenu)
	{
		if($menu3 == $submenu)
		{
			return '<li class="start active">';
		}
		else
		{
			return '<li>';
		}			
	}
	
	function retorna_submenu_active4($menu4,$submenu)
	{
		if($menu4 == $submenu)
		{
			return '<li class="start active">';
		}
		else
		{
			return '<li>';
		}			
	}
	
	function retorna_spam_selected($menu1,$menu)
	{
		if($menu1 == $menu)
		{
			return '<span class="selected"></span>';
		}
		else
		{
			return '<span class="arrow"></span>';
		}			
	}
	
?>
					
