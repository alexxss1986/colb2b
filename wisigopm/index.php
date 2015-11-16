<?php 
	session_cache_limiter('nocache');
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Wisigo Product Management</title>
<script type="text/javascript" src="js/controlloform.js"></script>

<!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		
		<!-- PLUGINS CSS -->
		<link href="assets/plugins/validator/bootstrapValidator.min.css" rel="stylesheet">

		
		<!-- MAIN CSS (REQUIRED ALL PAGE)-->
		<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="assets/css/style.css" rel="stylesheet">
		<link href="assets/css/style-responsive.css" rel="stylesheet">
        <!--<link type="text/css" rel="stylesheet" href="css/style.css" media="all" />-->
 
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
        
</head>
<?php 

if (!isset($_SESSION['username'])){
?>
<body class="login tooltips">
	
		
		
		
		<!--
		===========================================================
		BEGIN PAGE
		===========================================================
		-->
		<div class="login-header text-center">
			<img src="img/wisigo-logo.png" class="logo" alt="Logo">
		</div>
		<div class="login-wrapper">
			<form role="form" action="config/login.php" method="post" id="form_login">
				<div class="form-group">
				  <input type="text" name="username" class="form-control no-border input-lg rounded" placeholder="Enter username" autofocus>
				</div>
				<div class="form-group">
				  <input type="password" name="password" class="form-control no-border input-lg rounded" placeholder="Enter password">
				</div>
				<!--<div class="form-group">
				  <div class="checkbox">
					<label>
					  <input type="checkbox" class="i-yellow-flat">Ricordami
					</label>
				  </div>
				</div>-->
				<div class="form-group">
					<button type="submit" class="btn btn-success btn-lg btn-perspective btn-block">ACCEDI</button>
				</div>
			</form>
			<!--<p class="text-center"><strong><a href="forgot-password.html">Hai dimenticato la tua password?</a></strong></p>-->
			<!--<p class="text-center">or</p>-->
			<!--<p class="text-center"><strong><a href="register.html">Crea un nuovo account</a></strong></p>-->
		</div><!-- /.login-wrapper -->
		<!--
		===========================================================
		END PAGE
		===========================================================
		-->
		
		<!--
		===========================================================
		Placed at the end of the document so the pages load faster
		===========================================================
		-->
		<!-- MAIN JAVASRCIPT (REQUIRED ALL PAGE)-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/plugins/retina/retina.min.js"></script>
		<script src="assets/plugins/nicescroll/jquery.nicescroll.js"></script>
		<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<script src="assets/plugins/backstretch/jquery.backstretch.min.js"></script>
 
		<!-- PLUGINS -->
		<script src="assets/plugins/validator/bootstrapValidator.js"></script>
		
		
		
		<!-- MAIN APPS JS -->
		<script src="assets/js/apps.js"></script>
        <script src="assets/plugins/validator/example.js"></script>
		
	</body>
<?php 
}
else {
    if ($_SESSION['livello']!=1) {
        echo "<script type=\"text/javascript\">location.replace(\"dashboard.php\");</script>";
    }
    else {
        echo "<script type=\"text/javascript\">location.replace(\"ordini.php\");</script>";
    }
}
?>
</html>

