       <div class="pageheader">
<?php
if($_SESSION['intern-acces'] >= 2)
{
?>           <form action="results.html" method="post" class="searchbar">
                <a href="dashboard.php?pagina=adaugaproiect" class="btn btn-primary" style="color:white;">Anunta proiect nou</a>
            </form>
<?php
}
?>
            <div class="pageicon"><span class="iconfa-pushpin"></span></div>
            <div class="pagetitle">
                <h5>Proiectele noastre</h5>
                <h1>Proiecte</h1>
            </div>
        </div><!--pageheader-->

<?php
if($_SESSION['intern-acces'] == 3)
{
	if($_GET['del']!="")
	{
		del_project($_GET['del']);
	}
}
?>        
        <div class="maincontent">
            <div class="maincontentinner">

			<div class="row">
				<div id="dashboard-left" class="col-md-8">
                <h4 class="widgettitle">Proiecte</h4>
                <table class="table responsive">
                    <thead>
                        <tr>
                            <th>Nume</th>
							<th>Tip</th>
                            <th>Perioada</th>
                            <th>Actiuni</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$logs = get_projects($_GET['pag']);
foreach($logs as $log)
{
?>
<tr>
	<td><?php echo $log['nume']; ?></td>
	<td><?php echo project_category_data($log['categorie'])['nume']; ?></td>
	<td><?php if($log['datai'] == $log['dataf']){echo ddate($log['datai']);}else{echo ddate($log['datai']) . " - " . ddate($log['dataf']);} ?></td>
<td> <a href="dashboard.php?pagina=proiecte&pag=<?php echo (int) $_GET['pag']; ?>&show=<?php echo $log['ID']; ?>"><span class="iconfa-eye-open"></span></a><?php if($_SESSION['intern-acces'] >= 2){ ?>&nbsp;&nbsp;&nbsp;<a href="dashboard.php?pagina=editeazaproiect&id=<?php echo $log['ID']; ?>"><span class="iconfa-pencil"></span></a><?php } ?><?php if($_SESSION['intern-acces'] == 3){ ?>&nbsp;&nbsp;&nbsp;<a href="dashboard.php?pagina=proiecte&pag=<?php echo (int) $_GET['pag'] ?>&del=<?php echo $log['ID']; ?>"><span class="iconfa-remove"></span></a><?php } ?></td>
</tr>
<?php
}
?>
                    </tbody>
                </table>

<?php if(projects_prev_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=proiecte&pag=<?php echo ((int) $_GET['pag'] - 1); ?>" class="btn btn-primary" style="color:white;">Pagina anterioara</a>&nbsp;<?php } ?>
<?php if(projects_next_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=proiecte&pag=<?php echo ((int) $_GET['pag'] + 1); ?>" class="btn btn-primary" style="color:white;">Pagina urmatoare</a><?php } ?>

			</div>
			<div id="dashboard-left" class="col-md-4">
			<?php
			if(check_project_exists($_GET['show']))
			{
				?>
				<h4 class="widgettitle">Descrierea proiectului <?php echo get_project_data($_GET['show'])['nume'];?></h4>
                <div class="widgetcontent">
				<?php $d = get_project_data($_GET['show'])['descriere']; if($d != ""){echo $d;}else{echo "<em>Nu a fost adaugata nici o descriere.</em>";} ?> 
				</div>
				
				<h4 class="widgettitle">Participanti la proiectul <?php echo get_project_data($_GET['show'])['nume'];?> (<?php echo count_pax_project($_GET['show']); ?>)</h4>
                <div class="widgetcontent" style="overflow-x: hidden;overflow-y: auto;height:235px;">
						<?php
						$ranks = get_pax_project($_GET['show']);
						foreach($ranks as $rank)
						{
							echo '<span class="iconfa-user"></span> ' . show_user($rank['IDutilizator']) . " (".$rank['rank'].")<br />";
						}
						?>
				</div>
				
						<h4 class="widgettitle">Am participat la acest proiect</h4>
						<div class="widgetcontent">
						<?php
						if($_POST['go']=="y")
							part_project($_GET['show'],$_POST['nume']);
						$status = get_project_status_for_user($_GET['show']);
						if($status == 0)
						{
						?>
							<form method="post">
                                <span class="field">
                                     <input type="text" class="form-control input-default" name="nume" value="<?php echo $data['nume']; ?>" placeholder="Numele responsabilitatii tale">
                                </span>
                            <br />
										<input type="hidden" name="go" value="y"><button class="btn btn-primary">Am participat</button>
							</form>
						<?php
						}elseif($status == "-1"){
							echo "Participarea urmeaza sa-ti fie aprobata.";
						}else{
							echo "Esti marcat deja ca ai participat la proiect.";
						} ?>
						</div>
			<?php
			}
			?>
			</div>
								</div>