<?php

	if(!isset($_SESSION))
	{
		session_start();
	}
	
	if (isset($_SESSION['logado'])) 
	{
		if($_SESSION['logado'])
		{
			header("Location: inicio.php");
			exit;
		}
	}
	
?>

<!DOCTYPE html>

<!-- ÍNICIO HTML -->
<html lang="pt-BR" class="no-js">
	
	<!-- ÍNICIO HEAD -->
	<head>
		<meta charset="utf8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport"/>
		<meta content="" name="Sistema para Movimentação de Café"/>
		<meta content="" name="Luiz Guilherme Souza"/>
		
		<link rel="shortcut icon" href="assets/global/img/favicon.ico"/>
		<title>APAS - Movimentação de Café</title>
		
		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" />
		<link rel="stylesheet" type="text/css" href="assets/global/plugins/font-awesome/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" />
		<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="assets/global/plugins/uniform/css/uniform.default.css" />
		<!-- END GLOBAL MANDATORY STYLES -->
		
		<!-- BEGIN PAGE LEVEL STYLES -->
		<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css" />
		<link rel="stylesheet" type="text/css" href="assets/admin/pages/css/login3.css" />
		<!-- END PAGE LEVEL SCRIPTS -->
		
		<!-- BEGIN THEME STYLES -->
		<link rel="stylesheet" type="text/css" href="assets/global/css/components.css" id="style_components"/>
		<link rel="stylesheet" type="text/css" href="assets/global/css/plugins.css" />
		<link rel="stylesheet" type="text/css" href="assets/admin/layout/css/layout.css" />
		<link rel="stylesheet" type="text/css" href="assets/admin/layout/css/themes/default.css" id="style_color"/>
		<link rel="stylesheet" type="text/css" href="assets/admin/layout/css/custom.css" />
		<!-- END THEME STYLES -->
	</head>
	<!-- END HEAD -->
	
	<!-- BEGIN BODY -->
	<body class="login">
		<!-- BEGIN LOGO -->
		<div class="logo">
			<a href="index.html">
			<img src="assets/admin/layout2/img/logomovcoffe3.png" alt=""/>
			</a>
		</div>
		<!-- END LOGO -->
	
		<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
		<div class="menu-toggler sidebar-toggler">
		</div>
		<!-- END SIDEBAR TOGGLER BUTTON -->
		
		<!-- BEGIN LOGIN -->
		<div class="content">
			
			<!-- BEGIN LOGIN FORM -->
			<form class="login-form" action="getLogin.php" method="post">
				<h3 class="form-title">Faça login na sua conta</h3>
				
				<div class="alert alert-danger display-hide">
					<button class="close" data-close="alert"></button>
					<span>Entre com o usuário e a senha. </span>
				</div>
				
				<?php 
				
					if(isset($_GET['errologin']))
					{
										
				?>
				
					<div class="alert alert-danger">
						<button class="close" data-close="alert"></button>
						<span><?php echo $_GET['errologin']; ?> </span>
					</div>
				
				<?php 
				
					}
									
				?>
				
				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">Usuário</label>
					
					<div class="input-icon">
						<i class="fa fa-user"></i>
						<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Usuário" name="username"/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">Senha</label>
					
					<div class="input-icon">
						<i class="fa fa-lock"></i>
						<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Senha" name="password"/>
					</div>
				</div>
				
				<button type="submit" class="btn green-jungle pull-right">
					Entrar <i class="m-icon-swapright m-icon-white"></i>
				</button>
				
				<div class="form-actions"></div>
				</br>
			</form>
			<!-- END LOGIN FORM -->
			
		</div>
		<!-- END LOGIN -->
	
		<!-- BEGIN COPYRIGHT -->
		<div class="copyright">
			<div class="page-footer-inner">
				<b style="color: #ffffff">Desenvolvido por <a style="color: #98E080" >Luiz Guilherme Souza</a>.</br>
				&copy; Todos os Direitos Reservados.<b></b>
			</div>
			
			<div class="scroll-to-top">
				<i class="icon-arrow-up"></i>
			</div>
		</div>
		<!-- END COPYRIGHT -->
		
		<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
		<!-- BEGIN CORE PLUGINS -->
		<script type="text/javascript" src="assets/global/plugins/jquery.min.js"></script>
		<script type="text/javascript" src="assets/global/plugins/jquery-migrate.min.js"></script>
		<script type="text/javascript" src="assets/global/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="assets/global/plugins/jquery.blockui.min.js"></script>
		<script type="text/javascript" src="assets/global/plugins/uniform/jquery.uniform.min.js"></script>
		<script type="text/javascript" src="assets/global/plugins/jquery.cokie.min.js"></script>
		<!-- END CORE PLUGINS -->
		
		<!-- BEGIN PAGE LEVEL PLUGINS -->
		<script type="text/javascript" src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
		<!-- END PAGE LEVEL PLUGINS -->
		
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<script type="text/javascript" src="assets/global/scripts/metronic.js"></script>
		<script type="text/javascript" src="assets/admin/layout/scripts/layout.js"></script>
		<script type="text/javascript" src="assets/admin/layout/scripts/demo.js"></script>
		<script type="text/javascript" src="assets/admin/pages/scripts/login.js"></script>
		<!-- END PAGE LEVEL SCRIPTS -->
		
		<script>
			jQuery(document).ready(function() {     
				  Metronic.init(); 	// init metronic core components
				  Layout.init(); 	// init current layout
				  Login.init();
				  Demo.init();
			});
		</script>
		<!-- END JAVASCRIPTS -->
	</body>
	<!-- END BODY -->
</html>