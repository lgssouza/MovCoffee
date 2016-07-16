<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner container">
		
		<!-- BEGIN LOGO -->
		<div class="page-logo" align="center">
			<a href="index.php">				
				<img src="assets/admin/layout2/img/logomovcoffe3.png" style="width: 120px; height: 45px; margin-top: 13px" alt="logo" class="logo-default"/><!-- style="width: 120px; height: 40px; margin-top: 13px" -->
			</a>
			
			<div class="menu-toggler sidebar-toggler">
				<!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
			</div>
		</div>
		<!-- END LOGO -->
		
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
			
		<!-- BEGIN PAGE TOP -->
		<div class="page-top">
			
			<!-- BEGIN HEADER SEARCH BOX -->
			<!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
			<form class="search-form search-form-expanded" action="extra_search.html" method="GET">
				<div class="input-group">
					<!--<input type="text" class="form-control" placeholder="Pesquisar..." name="query">
					
					<span class="input-group-btn">
						<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
					</span>-->
				</div>
			</form>
			<!-- END HEADER SEARCH BOX -->
			
			<!-- BEGIN TOP NAVIGATION MENU -->
			<div class="top-menu">
				<?php
					$sql 	= "SELECT b.subg_descricao, a.id_subgrupo_valores, a.subg_total_saldo FROM tb_pdcj_subgrupo_rel_valores a inner join tb_pdcj_subgrupo_rel b on a.fk_id_subg = b.id_subgrupo where a.subg_total_saldo <= 500 and a.subg_total_orcado > 0";			
				
					$rs 	= $mysqli->query($sql);	
		
					if($rs)
					{
						$regs = array();
		        
						while($reg = $rs->fetch_object())
						{
							array_push($regs, $reg);
						}
				
						$rs->close();
					}
									
				?>
				<ul class="nav navbar-nav pull-right">
					<li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<i class="icon-bell"></i>
						<span class="badge badge-default">
						<?php echo count($regs) ?> </span>
						</a>
						<ul class="dropdown-menu">
							<li class="external">
								<h3><span class="bold"><?php echo count($regs) ?></span> Subgrupos com Saldo Total menor que R$ 500,00</h3>								
							</li>
							<li>
								<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
									<?php
									
										for ($i=0; $i <count($regs); $i++) 
										{
											
											echo "<li>
													<a href=\"detalhe_lancamentos_gastos_rel.php?id_subgrupo=" . $regs[$i] -> id_subgrupo_valores . "\">										
														<span class=\"details\">																														
															
															" . $regs[$i] -> subg_descricao . "
													 	</span>
													</a>
												</li>									
												"; 
											
										}
									
									?>
									
								</ul>
							</li>
						</ul>
					</li>

					<!-- BEGIN NOTIFICATION DROPDOWN -->					
					<li class="dropdown dropdown-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
													
							<span class="username username-hide-on-mobile">
								<?php echo $_SESSION['nome_usuario']; ?> 
							</span>
						
							<i class="fa fa-angle-down"></i>
						</a>
						
						<ul class="dropdown-menu dropdown-menu-default">
							<!--
							<li>
								<a href="extra_profile.html">
									<i class="icon-user"></i> Minha Conta
								</a>
							</li>
							
							<li>
								<a href="page_calendar.html">
									<i class="icon-calendar"></i> Meu Calendario 
								</a>
							</li>
							
							<li>
								<a href="inbox.html">
									<i class="icon-envelope-open"></i> 
									Mensagens
									
									<span class="badge badge-danger">
										1 
									</span>
								</a>
							</li>
														
							<li>
								<a href="page_todo.html">
									<i class="icon-rocket"></i> 
									Tarefas
								
									<span class="badge badge-success">
										3 
									</span>
								</a>
							</li>
							
							<li class="divider"></li>
							-->
							
							<li>
								<a href="getLogout.php">
									<i class="fa fa-sign-out"></i> Sair
								</a>
							</li>
						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
					
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte
					<li class="dropdown dropdown-extended quick-sidebar-toggler">
	                    <span class="sr-only">Toggle Quick Sidebar</span>
	                    <i class="icon-logout"></i>
	                </li>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>
				
			</div>
			<!-- END TOP NAVIGATION MENU -->
			
		</div>
		<!-- END PAGE TOP -->
		
	</div>
	<!-- END HEADER INNER -->
	
</div>
<!-- END HEADER -->

<div class="clearfix">
</div>