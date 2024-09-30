       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-money"></span></div>
            <div class="pagetitle">
                <h5>Web Fundraising Platform</h5>
                <h1>FRozen</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
				<div class="maincontentinner">
<div class="row">
				
					<div id="dashboard-left" class="col-md-8">
						<h4 class="widgettitle">Descriere</h4>
						<div class="widgetcontent">
						    <strong><a href="https://frozen.bestbrasov.ro/" target="_blank">FRozen</a></strong> este o aplicatie web interna cu ajutorul careia facem fundraising in LBG.
</div></div>

<div id="dashboard-left" class="col-md-4">
						<h4 class="widgettitle">Status</h4>
						<div class="widgetcontent">
<?php
$y = file_get_contents("https://frozen.bestbrasov.ro/api.php?email=" . urlencode($_SESSION['intern-email']));
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
