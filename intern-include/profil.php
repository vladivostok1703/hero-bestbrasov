<?php
if(!check_exists($_GET['id']))
{
	include "404.php";
	die();
}

$data = get_user_data($_GET['id']);
?>       <div class="pageheader">
<?php
if($_SESSION['intern-acces'] == 2)
{
?>           <form method="post" class="searchbar">
                <a href="dashboard.php?pagina=moduseri&utilizator=<?php echo (int) $_GET['id']; ?>" class="btn btn-primary" style="color:white;">Modifica datele</a>
            </form>
<?php
}elseif($_SESSION['intern-acces'] == 3)
{
	if($_GET['ban']=="true")
	{
		ban_user($_GET['id']);
	}elseif($_GET['unban']=="true")
	{
		unban_user($_GET['id']);
	}
?>           <form method="post" class="searchbar">
                <a href="dashboard.php?pagina=moduseri&utilizator=<?php echo (int) $_GET['id']; ?>" class="btn btn-primary" style="color:white;">Modifica datele</a>
				<?php
					if(check_auth_by_id($_GET['id']))
					{
						echo '<a href="dashboard.php?pagina=profil&id='.(int) $_GET['id'].'&ban=true"  class="btn btn-primary" style="color:white;">Blocheaza accesul</a>';
					}else{
						echo '<a href="dashboard.php?pagina=profil&id='.(int) $_GET['id'].'&unban=true"  class="btn btn-primary" style="color:white;">Deblocheaza accesul</a>';
					}
				?>
            </form>
<?php
}
?>
            <div class="pageicon"><span class="iconfa-user"></span></div>
            <div class="pagetitle">
                <h5><?php echo get_lbg_status($data['statut']); ?></h5>
                <h1><?php echo $data['nume']; ?></h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">

				<div class="row">
				<div id="dashboard-left" class="col-md-9">
                <h4 class="widgettitle">General</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2">
						    <p>
                                <label>Nume</label>
                                <span class="field">
					<?php echo $data['nume']; ?>
                                </span>
                            </p>
						    <p>
                                <label>Data nasterii</label>
                                <span class="field">
					<?php if($data['datanasterii'] != ""){echo ddate($data['datanasterii']);}else{echo "<em>Nesetat</em>";} ?>
                                </span>
                            </p>
						    <p>
                                <label>Numar de telefon</label>
                                <span class="field">
					<?php if($data['nrtelefon'] != ""){echo show_phone($data['nrtelefon']);}else{echo "<em>Nesetat</em>";} ?>
                                </span>
                            </p>
						    <p>
                                <label>Email</label>
                                <span class="field">
					<?php echo $data['email']; ?>
                                </span>
                            </p>							
                    </form>
                </div>
				</div>
				
				<div id="dashboard-left" class="col-md-3" style="text-align:right;">
					<img src="<?php echo $data['poza']; ?>" style="height:250px;">	
				</div>
				</div>
				
				<div class="row">
				<div id="dashboard-left" class="col-md-6">
                <h4 class="widgettitle">Detalii generatie</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2">
						    <p>
                                <label>Data intrarii in BEST</label>
                                <span class="field">
					<?php if($data['databest'] != ""){ echo $data['databest'];}else{echo "<em>Nesetat</em>";} ?>
                                </span>
                            </p>
			    <p>
                                <label>Numele generatiei</label>
                                <span class="field">
					<?php if($data['numelegeneratiei'] != ""){ echo $data['numelegeneratiei'];}else{echo "<em>Nesetat</em>";} ?>
                                </span>
                            </p>
			    <p>
				<label>Mentori <?php if($_SESSION['intern-acces'] >= 2) { ?><a href="dashboard.php?utilizator=<?php echo (int) $_GET['id']; ?>&pagina=mentori"><span class="iconfa-pencil"></span></a><?php } ?></label>
                                <span class="field">
                            <?php
				$mentors = get_mentors($_GET['id']);
				if($mentors == "")
				{
					echo "<em>Niciun mentor nu a fost adaugat.</em>";
				}else{
					$men = array();
					foreach($mentors as $mentor)
						$men[] = show_user($mentor['IDmentor']);
					echo implode(", ",$men);
				}
				?>
                                </span>
                            </p>
                    </form>
                </div>
				</div>
				
				<div id="dashboard-left" class="col-md-6">
                <h4 class="widgettitle">Detalii cont</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2">						
						    <p>
                                <label>Cont activ pe platforma</label>
                                <span class="field">
                                    <?php
					if(check_auth_by_email($data['email']))
					{
						echo "da";
					}else{
						echo "nu";
					}
				    ?>
                                </span>
                            </p>
			    <p>
                                <label>Ultima activitate</label>
                                <span class="field">
                                    <?php
					if($data['activitate'] == 0)
						echo "<em>Niciodata</em>";
					else
						echo return_time($data['activitate']);
				?>
                                </span>
                            </p>
			    <p>
                                <label>Tip acces</label>
                                <span class="field">
                                    <?php echo get_rank($data['acces']); ?>
                                </span>
                            </p>
                    </form>
                </div>
				</div>
				</div>	

               <h4 class="widgettitle">Activitate</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2">
			    <p>
                                <label>Evenimente  (<?php echo (int) count_events_for_user($_GET['id']); ?>)</label>
                                <span class="field">
							<?php echo get_events_for_user($_GET['id']); ?>
                                </span>
                            </p>
			    <p>
                                <label>Proiecte (<?php echo (int) count_projects_for_user($_GET['id']); ?>)</label>
                                <span class="field">
							<?php echo get_projects_for_user($_GET['id']); ?>
                                </span>
                            </p>
			    <p>
                                <label>Sedinte (<?php echo (int) count_meetings_for_user($_GET['id']); ?>)</label>
                                <span class="field">
							<?php echo get_meetings_for_user($_GET['id']); ?>&nbsp;
                                </span>
                            </p>
			    <p>
                                <label>Sesiuni de training (<?php echo (int) count_trainings_for_user($_GET['id']); ?>) <?php if($_SESSION['intern-acces'] >= 2) { ?><a href="dashboard.php?utilizator=<?php echo (int) $_GET['id']; ?>&pagina=modtraining"><span class="iconfa-pencil"></span></a><?php } ?></label>
                                <span class="field">
					<?php echo get_trainings_for_user($_GET['id']); ?>&nbsp;
                                </span>
                            </p>							
                    </form>
                </div>
			
