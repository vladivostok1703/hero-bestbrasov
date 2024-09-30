<?php
include "intern-core/config.php";

if($_SESSION['intern-name'] == "")
{
	$_SESSION['intern-url'] = get_full_url();
	header("Location: index.php");
	exit();
}
if($_SESSION['intern-url'] != "")
{
	$url = $_SESSION['intern-url'];
	$_SESSION['intern-url'] = "";
	header("Location: $url");
	die();
}

update_activity();

/* In anumite cazuri sunt generate warning-uri, prin randurile astea 
avem grija sa nu creeze un fisier cu astea si sa ocupe spatiul pe server */
if(file_exists("error_log"))
{
	unlink("error_log");
}
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title>HeRo - Panou Intern BEST Brasov</title>

<link rel="stylesheet" href="intern-design/css/style.default.css" />
<link rel="stylesheet" href="intern-design/css/responsive-tables.css">
    
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
<script src="intern-design/js/jquery.uniform.min.js"></script>
<script src="intern-design/js/flot/jquery.flot.min.js"></script>
<script src="intern-design/js/flot/jquery.flot.resize.min.js"></script>
<script src="intern-design/js/responsive-tables.js"></script>
<script src="intern-design/js/jquery.tagsinput.min.js"></script>
<script src="intern-design/js/jquery.slimscroll.js"></script>
<script src="intern-design/js/custom.js"></script>

<!--[if lte IE 8]>
<script src="intern-design/js/excanvas.min.js"></script>
<![endif]-->

<style type="text/css">
.logo .title
{
	font-size:67px;
	color:white;
}
.logo .subtitle
{
	font-size:12px;
	color:white;
}
</style>

</head>

<body>

<div id="mainwrapper" class="mainwrapper">
    
    <div class="header">
        <div class="logo">
			<span class="title">HeRo</span><br />
			<span class="subtitle">Panou Intern BEST Brasov</span>
	</div>
        <div class="headerinner">
            <ul class="headmenu">
                <li class="odd">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#">
                    <span class="count"><?php echo count_online_users(); ?></span>
                    <span class="head-icon head-users"></span>
                    <span class="headmenu-label">Utilizatori Activi</span>
                    </a>
                    <ul class="dropdown-menu newusers">
			<li class="nav-header">Utilizatori Activi</li>
					<?php
					$online_users = online_users();
					foreach($online_users as $online_user)
					{
					?>
                        <li>
                            <a href="dashboard.php?pagina=profil&id=<?php echo $online_user['ID']; ?>">
                                <img src="<?php echo $online_user['poza'] ?>" alt="" class="userthumb" />
                                <strong><?php echo $online_user['nume']; ?></strong>
                                <small><?php echo return_time($online_user['activitate']); ?></small>
                            </a>
                        </li>					
					<?php
					}
					?>
                    </ul>
                </li>
                <li class="right">
                    <div class="userloggedinfo">
                        <img src="<?php echo $_SESSION['intern-poza']; ?>" alt="" />
                        <div class="userinfo">
                            <h5><?php echo $_SESSION['intern-name']; ?></h5>
                            <ul>
								<li><a href="dashboard.php?pagina=profil&id=<?php echo $_SESSION['intern-id']; ?>">Profilul meu</a></li>
                                <li><a href="dashboard.php?pagina=setari">Setari de cont</a></li>
                                <li><a href="logout.php">Deconectare</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul><!--headmenu-->
        </div>
    </div>
    
    <div class="leftpanel">
        
        <div class="leftmenu">        
            <ul class="nav nav-tabs nav-stacked">
            	<li class="nav-header">Navigare</li>
                <li><a href="dashboard.php"><span class="iconfa-laptop"></span> Acasa</a></li>
<?php
if($_SESSION['intern-acces'] >= 2)
{
?>
                <li class="dropdown"><a href=""><span class="iconfa-cogs"></span> Administrare platforma</a>
                	<ul>
                    	<?php if($_SESSION['intern-acces'] == 3){ ?><li><a href="dashboard.php?pagina=adminauth">Autorizari accese pe platforma</a></li><?php } ?>
						<?php if($_SESSION['intern-acces'] == 3){ ?><li><a href="dashboard.php?pagina=adminproiecte">Administrare categorii de proiecte</a></li><?php } ?>
						<?php if($_SESSION['intern-acces'] == 3){ ?><li><a href="dashboard.php?pagina=adminsedinte">Administrare categorii de sedinte</a></li><?php } ?>
                    	<?php if($_SESSION['intern-acces'] == 3){ ?><li><a href="dashboard.php?pagina=admintraining">Administrare categorii de training</a></li><?php } ?>
                    	<li><a href="dashboard.php?pagina=logs">Log-uri administrative</a></li>
                    </ul>
                </li>
<?php
}
?>
<?php
if($_SESSION['intern-acces'] >= 2)
{
?>
                <li class="dropdown"><a href=""><span class="iconfa-cogs"></span> Administrare utilizatori</a>
                	<ul>
                    	<li><a href="dashboard.php?pagina=moduseri">Modifica datele utilizatorilor</a></li>
                    	<li><a href="dashboard.php?pagina=mentori">Modifica mentorii utilizatorilor</a></li>
                    	<li><a href="dashboard.php?pagina=modtraining">Modifica training-urile utilizatorilor</a></li>
                    </ul>
                </li>
<?php
}
?>
<?php
if($_SESSION['intern-acces'] >= 2)
{
	$s1 = check_trainings_app();
	$s2 = check_meetings_app();
	$s3 = check_projects_app();
	$s4 = check_events_app();
	$rez = $s1 + $s2 + $s3 + $s4;
?>
                <li class="dropdown"><a href=""><span class="iconfa-cogs"></span> Aprobari (<?php echo $rez; ?>)</a>
                	<ul>
                    	<li><a href="dashboard.php?pagina=appevenimente">Evenimente (<?php echo $s4; ?>)</a></li>
                    	<li><a href="dashboard.php?pagina=appproiecte">Proiecte (<?php echo $s3; ?>)</a></li>
                    	<li><a href="dashboard.php?pagina=appsedinte">Sedinte (<?php echo $s2; ?>)</a></li>
                    	<li><a href="dashboard.php?pagina=apptraining">Sesiuni de training (<?php echo $s1; ?>)</a></li>
                    </ul>
                </li>
<?php
}
?>
                <li><a href="dashboard.php?pagina=evenimente"><span class="iconfa-group"></span>&nbsp;Evenimente</a></li>
                <li><a href="dashboard.php?pagina=proiecte"><span class="iconfa-pushpin"></span>&nbsp;&nbsp;Proiecte</a></li>
                <li><a href="dashboard.php?pagina=sedinte"><span class="iconfa-comments"></span> Sedinte</a></li>
                <li><a href="dashboard.php?pagina=trainings"><span class="iconfa-book"></span> Sesiuni de training</a></li>
                <li><a href="dashboard.php?pagina=utilizatori"><span class="iconfa-user"></span> Utilizatori</a></li>
		<li><a href="dashboard.php?pagina=changelog"><span class="iconfa-calendar"></span> Changelog</a></li>
                <li><a href="logout.php"><span class="iconfa-off"></span> Deconectare</a></li>
				
            </ul>
        </div><!--leftmenu-->
        
    </div><!-- leftpanel -->
    
    <div class="rightpanel">
    <?php
	$pagina = preg_replace("/[^a-zA-Z0-9]+/", "", $_GET['pagina']);
	if($pagina == "")
		$pagina = "index";
	
	if(file_exists("intern-include/" . $pagina . ".php"))
		include "intern-include/" . $pagina . ".php";
	else
		include "intern-include/404.php";

	?>
                <div class="footer">
                    <div class="footer-left">
                        <span>HeRo - versiunea <a href="dashboard.php?pagina=changelog"><?php echo $ver; ?></a></span>
                    </div>
                    <div class="footer-right">
                        <span>Creat de <a href="mailto:dragos.gaftoneanu@gmail.com">Dragos Gaftoneanu</a></span>
                    </div>
                </div><!--footer-->
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
        
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
</body>
</html>
