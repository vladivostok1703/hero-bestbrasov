<?php
if($_SESSION['intern-acces'] < 2)
{
	include "404.php";
	die();
}
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-cogs"></span></div>
            <div class="pagetitle">
                <h5>Aproba participarea la evenimente</h5>
                <h1>Evenimente</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">

                <h4 class="widgettitle">Evenimente</h4>
                <table class="table responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilizator</th>
                            <th>Eveniment</th>
							<th>Responsabilitate</th>
                            <th>Actiuni</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
if($_GET['app']!="")
	app_event($_GET['app']);

if($_GET['rem']!="")
	rem_event($_GET['rem']);
$logs = get_eve($_GET['pag']);
foreach($logs as $log)
{
?>
<tr>
	<td><?php echo $log['ID']; ?></td>
	<td><?php echo show_user($log['IDutilizator']); ?></td>
	<td><?php echo get_event_data($log['IDproiect'])['nume'] ?></td>
	<td><?php echo $log['rank']; ?></td>
	<td><a href="dashboard.php?pagina=appevenimente&app=<?php echo $log['ID']; ?>">aproba</a> | <a href="dashboard.php?pagina=appevenimente&rem=<?php echo $log['ID']; ?>">respinge</a></td>
<?php
}
?>
                    </tbody>
                </table>

<?php if(eve_prev_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=appevenimente&pag=<?php echo ((int) $_GET['pag'] - 1); ?>" class="btn btn-primary" style="color:white;">Pagina anterioara</a>&nbsp;<?php } ?>
<?php if(eve_next_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=appevenimente&pag=<?php echo ((int) $_GET['pag'] + 1); ?>" class="btn btn-primary" style="color:white;">Pagina urmatoare</a><?php } ?>
