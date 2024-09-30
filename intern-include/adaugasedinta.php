<?php
if($_SESSION['intern-acces'] == 1)
{
	include "404.php";
	die();
}
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-comments"></span></div>
            <div class="pagetitle">
                <h5>Anunta o sedinta noua</h5>
                <h1>Anunta sedinta noua</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
			<?php
			if($_POST['submit']=="y")
			{
				echo add_meeting($_POST['csrf'],$_POST['nume'],$_POST['data'],$_POST['categorie']);
			}
			?>
                <h4 class="widgettitle">Anunta sedinta noua</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post">
						    <p>
                                <label>Numele sedintei</label>
                                <span class="field">
                                     <input type="text" class="form-control input-default" name="nume">
                                </span>
                            </p>
						    <p>
                                <label>Data sedintei</label>
                                <span class="field"><script type="text/javascript">jQuery(document).ready(function(){jQuery("#dp").datepicker({ dateFormat: 'yy-mm-dd' });});</script>
                                     <input type="text" class="form-control input-default" name="data" placeholder="AAAA-LL-ZZ" id="dp">
                                </span>
                            </p>
						    <p>
                                <label>Categorie</label>
                                <span class="field">
                                     <select name="categorie" style="width:100%;">
									 <?php
									 $categorii = get_meeting_categories();
									 foreach($categorii as $categorie)
									 {
										echo '<option value="'.$categorie['ID'].'">'.$categorie['nume'].'</option>';
									 }
									 ?>
									 </select>
                                </span>
                            </p>
                            <p class="stdformbutton">
                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><input type="hidden" name="submit" value="y"><button class="btn btn-primary">Anunta</button>
                            </p>
                    </form>
                </div>
				

