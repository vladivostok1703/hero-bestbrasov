       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-pencil"></span></div>
            <div class="pagetitle">
                <h5>Platforma pentru site-uri</h5>
                <h1>Wordpress</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
				<div class="maincontentinner">
<div class="row">
				
					<div id="dashboard-left" class="col-md-8">
						<h4 class="widgettitle">Descriere</h4>
						<div class="widgetcontent">
						    <strong><a href="https://bestbrasov.ro/wp-admin" target="_blank">Wordpress</a></strong> este o aplicatie web cu ajutorul careia creem si administram toate site-urile publice ale organizatiei.
</div></div>

<div id="dashboard-left" class="col-md-4">
						<h4 class="widgettitle">Status</h4>
						<div class="widgetcontent">
<?php
$y = file_get_contents("https://bestbrasov.ro/api.php?email=" . urlencode($_SESSION['intern-email']));
?>
Status: <?php
if($y == "1")
{
?><span style="color:green;font-weight:bold;">autorizat</span><?php
}else{
?><span style="color:red;font-weight:bold;">neautorizat</span><?php
}
?>
</div>
					
					
				</div> </div>
