<?php
if($_SESSION['intern-acces'] == 1)
{
	include "404.php";
	die();
}
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-group"></span></div>
            <div class="pagetitle">
                <h5>Anunta un eveniment nou</h5>
                <h1>Anunta eveniment nou</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
			<?php
			if($_POST['submit']=="y")
			{
				echo add_event($_POST['csrf'],$_POST['nume'],$_POST['descriere'],$_POST['datai'],$_POST['dataf'],$_POST['categorie']);
			}
			?>
                <h4 class="widgettitle">Anunta eveniment nou</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post">
						    <p>
                                <label>Numele evenimentului</label>
                                <span class="field">
                                     <input type="text" class="form-control input-default" name="nume">
                                </span>
                            </p>
						    <p>
                                <label>Descrierea evenimentului</label>
                                <span class="field">
                                     <textarea class="form-control input-default" name="descriere"></textarea>
                                </span>
                            </p>							
						    <p>
                                <label>Data de inceput</label>
                                <span class="field"><script type="text/javascript">jQuery(document).ready(function(){jQuery("#dp").datepicker({ dateFormat: 'yy-mm-dd' });});</script>
                                     <input type="text" class="form-control input-default" name="datai" placeholder="AAAA-LL-ZZ" id="dp">
                                </span>
                            </p>
						    <p>
                                <label>Data de final</label>
                                <span class="field"><script type="text/javascript">jQuery(document).ready(function(){jQuery("#dp2").datepicker({ dateFormat: 'yy-mm-dd' });});</script>
                                     <input type="text" class="form-control input-default" name="dataf" placeholder="AAAA-LL-ZZ" id="dp2">
                                </span>
                            </p>							
                            <p class="stdformbutton">
                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><input type="hidden" name="submit" value="y"><button class="btn btn-primary">Anunta</button>
                            </p>
                    </form>
                </div>
				

