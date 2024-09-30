<?php
/* 
Name:		HeRo - index
Description:	Pagina de login pentru platforma de HR
Author:		dragos.gaftoneanu@gmail.com
*/

include "intern-core/config.php"; //fisier principal de configurari si functii
include "intern-core/google/settings.php"; //fisier pentru folosirea Google Auth

/* Daca persoana este deja conectata pe platforma, o redirectionam spre panou */
if($_SESSION['intern-name'] != "")
{
	header("Location: dashboard.php");
	exit();
}


?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title>HeRo - Panou Intern BEST Brasov</title>

<link rel="stylesheet" href="intern-design/css/style.default.css" type="text/css" />
<link rel="stylesheet" href="intern-design/css/login.css" type="text/css" />
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="intern-design/js/html5shiv.js"></script>
<script src="intern-design/js/respond.min.js"></script>
<![endif]-->

<script src="intern-design/js/jquery-1.10.2.min.js"></script>
<script src="intern-design/js/jquery-migrate-1.2.1.min.js"></script>
<script src="intern-design/js/jquery-ui-1.10.3.min.js"></script>
<script src="intern-design/js/bootstrap.min.js"></script>
<script src="intern-design/js/modernizr.min.js"></script>
<script src="intern-design/js/jquery.cookies.js"></script>
<script src="intern-design/js/custom.js"></script>

</head>

<body class="loginpage">

<div class="loginpanel">
    <div class="loginpanelinner">
        
        <div class="logo">
			<span class="title">HeRo</span><br />
			<span class="subtitle">Panou Intern BEST Brasov</span>
		</div>
       <center style="padding-top:50px;"><a class="login" href="<?php echo 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online' ?>" style="color:white;font-size:25px;font-weight:bold;">Conectare prin Google</a></center>
        <?php
if($_GET['page']=="error")
{
?><br /><br />
<center><span class="error-message">EROARE<br />Contul nu este autorizat sa accese platforma.</span></center>
<?php } ?>
    </div><!--loginpanelinner-->
</div><!--loginpanel-->

<div class="loginfooter">
    <p>Copyright &copy; 2017-<?php echo date("Y"); ?> <a href="https://hero.bestbrasov.ro">HeRo</a> - versiunea <?php echo $ver; ?>.<br />Creat de <a href="mailto:dragos.gaftoneanu@gmail.com">Dragos Gaftoneanu</a></p>
</div>

</body>
</html>
