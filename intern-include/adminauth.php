<?php

if($_SESSION['intern-acces'] != 3)

{

	include "404.php";

	die();

}

$emails = get_auth_emails();
?>       
        <div class="pageheader">
<div class="pageicon"><span class="iconfa-cogs"></span></div>
            <div class="pagetitle">
                <h5>Oameni care au acces la HeRo</h5>
                <h1>Administrare autorizari</h1>
            </div>
        </div><!--pageheader-->
        
		<script type="text/javascript">
		jQuery(document).ready(function(){jQuery('#tagss').tagsInput();});
		</script>
        <div class="maincontent">
            <div class="maincontentinner">
            
			<?php
			if($_POST['go']=="y")
			{
				echo add_auth_email($_POST['csrf'],$_POST['emails']);$emails = get_auth_emails();
			}
			?>
            <div class="widgetbox">
                <h4 class="widgettitle">Administrare autorizari</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post" action="">
                            <p>
                                <label>Email-uri autorizate</label>
                                <span class="field">
                                    <input name="emails" id="tagss" value="<?php echo implode(",",$emails); ?>">
                                </span>
                            </p>
							
							<p>
								<label>Cine nu s-a conectat<br /><small><a href="dashboard.php?pagina=adminman">Creeaza manual conturile</a></small></label>
								<span class="field">
									<?php echo check_unauth(); ?>
								</span>
							</p>
                                                    
                            <p class="stdformbutton">
                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><input type="hidden" name="go" value="y"><button class="btn btn-primary">Salveaza</button>
                            </p>
                    </form>
                </div>
            </div>
