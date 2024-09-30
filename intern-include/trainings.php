
<div class="pageheader">
<div class="pageicon"><span class="iconfa-book"></span></div>
            <div class="pagetitle">
                <h5>Vezi sesiunile de training din BEST</h5>
                <h1>Sesiuni de training</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
				<div class="row">
					<div id="dashboard-left" class="col-md-8">
<?php
$categorii = get_training_categories();
?>
                <div class="peoplelist">

<?php
$i=0;
foreach($categorii as $categorie)
{
	$i++;
	if($i % 3 == 1)
	{
		echo '<div class="row">';
	}
?>
                        <div class="col-md-4" >
                            <div class="peoplewrapper" style="height:100px;">
                                <div class="peopleinfo" style="margin-left:0px;">
                                    <h4><?php echo $categorie['nume']; ?></h4>
                                    <ul>
 					<li>&bull; <a href="dashboard.php?pagina=trainings&show=<?php echo $categorie['ID']; ?>">Vezi detalii</a><br />
                                    </ul>
                                </div>
                            </div>
                        </div>
<?php
	if($i % 3 == 0 || $i == count($categorii))
	{
		echo '</div>';
	}
}
?>
					</div></div>
					<div id="dashboard-left" class="col-md-4" style="margin-top:25px;">
                            <?php
							if($_GET['show']!="")
							{
								if($_POST['go'] == "y")
									add_training($_GET['show'],$_POST['dp']);
								?>
						<h4 class="widgettitle">Participanti la <?php echo get_category($_GET['show'])['nume'];?> (<?php echo count_trainings($_GET['show']); ?>)</h4>
                        <div class="widgetcontent" style="overflow-x: hidden;overflow-y: auto;height:235px;">
						<?php
						$ranks = get_trainings($_GET['show']);
						foreach($ranks as $rank)
						{
							echo '<span class="iconfa-user"></span> ' . show_user($rank['IDutilizator']) . " (".ddate($rank['data']).")<br />";
						}
						?>
                        </div>			

						<h4 class="widgettitle">Am participat la aceasta sesiune</h4>
						<?php
						$status = check_participation($_GET['show']);
						if($status == 0)
						{
						?>
						<div class="widgetcontent nopadding"><script type="text/javascript">jQuery(document).ready(function(){jQuery("#dp").datepicker({ dateFormat: 'yy-mm-dd' });});</script>
							<form class="stdform stdform2" method="post">
									<p>
										<label>Data la care ai participat</label>
										<span class="field">
											<input id="dp" type="text" placeholder="AAAA-LL-ZZ" name="dp" class="form-control" style="width:100px;" value="<?php echo date("Y-m-d"); ?>" />
							            </span>
									</p>
								    <p class="stdformbutton">
										<input type="hidden" name="go" value="y"><button class="btn btn-primary">Adauga</button>
									</p>
							</form>
						</div>
						<?php
						}elseif($status == -1){
							?>
							<div class="widgetcontent">
								Training-ul urmeaza sa-ti fie aprobat.
							</div>
							<?php
						}else{
							?>
							<div class="widgetcontent">
								Training-ul ti-a fost adaugat la profil.
							</div>								
							<?php
						}
						}
							?>
					</div>
				</div>
