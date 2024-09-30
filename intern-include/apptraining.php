<?php
if($_SESSION['intern-acces'] < 2)
{
	include "404.php";
	die();
}
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-cogs"></span></div>
            <div class="pagetitle">
                <h5>Aproba sesiuni de training</h5>
                <h1>Sesiuni de training</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">

                <h4 class="widgettitle">Sesiuni de training</h4>
                <table class="table responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilizator</th>
                            <th>Training</th>
							<th>Data</th>
                            <th>Actiuni</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
if($_GET['app']!="")
	app_trn($_GET['app']);

if($_GET['rem']!="")
	rem_trn($_GET['rem']);
$logs = get_trn($_GET['pag']);
foreach($logs as $log)
{
?>
<tr>
	<td><?php echo $log['ID']; ?></td>
	<td><?php echo show_user($log['IDutilizator']); ?></td>
	<td><?php echo get_category($log['IDtraining'])['nume']; ?></td>
	<td><?php echo ddate($log['data']); ?></td>
	<td><a href="dashboard.php?pagina=apptraining&app=<?php echo $log['ID']; ?>">aproba</a> | <a href="dashboard.php?pagina=apptraining&rem=<?php echo $log['ID']; ?>">respinge</a></td>
<?php
}
?>
                    </tbody>
                </table>

<?php if(trn_prev_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=apptraining&pag=<?php echo ((int) $_GET['pag'] - 1); ?>" class="btn btn-primary" style="color:white;">Pagina anterioara</a>&nbsp;<?php } ?>
<?php if(trn_next_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=apptraining&pag=<?php echo ((int) $_GET['pag'] + 1); ?>" class="btn btn-primary" style="color:white;">Pagina urmatoare</a><?php } ?>
