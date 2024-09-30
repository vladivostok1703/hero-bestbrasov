<?php
if($_SESSION['intern-acces'] < 2)
{
	include "404.php";
	die();
}
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-cogs"></span></div>
            <div class="pagetitle">
                <h5>Aproba participarea la sedinte</h5>
                <h1>Sedinte</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">

                <h4 class="widgettitle">Sedinte</h4>
                <table class="table responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilizator</th>
                            <th>Sedinta</th>
                            <th>Actiuni</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
if($_GET['app']!="")
	app_meeting($_GET['app']);

if($_GET['rem']!="")
	rem_meeting($_GET['rem']);
$logs = get_met($_GET['pag']);
foreach($logs as $log)
{
?>
<tr>
	<td><?php echo $log['ID']; ?></td>
	<td><?php echo show_user($log['IDutilizator']); ?></td>
	<td><?php echo get_meeting_data($log['IDsedinta'])['nume'] ?></td>
	<td><a href="dashboard.php?pagina=appsedinte&app=<?php echo $log['ID']; ?>">aproba</a> | <a href="dashboard.php?pagina=appsedinte&rem=<?php echo $log['ID']; ?>">respinge</a></td>
<?php
}
?>
                    </tbody>
                </table>

<?php if(met_prev_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=appsedinte&pag=<?php echo ((int) $_GET['pag'] - 1); ?>" class="btn btn-primary" style="color:white;">Pagina anterioara</a>&nbsp;<?php } ?>
<?php if(met_next_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=appsedinte&pag=<?php echo ((int) $_GET['pag'] + 1); ?>" class="btn btn-primary" style="color:white;">Pagina urmatoare</a><?php } ?>
