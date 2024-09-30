<?php
if($_SESSION['intern-acces'] < 2)
{
	include "404.php";
	die();
}
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-cogs"></span></div>
            <div class="pagetitle">
                <h5>Aproba participarea la proiecte</h5>
                <h1>Proiecte</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">

                <h4 class="widgettitle">Proiecte</h4>
                <table class="table responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilizator</th>
                            <th>Proiect</th>
							<th>Responsabilitate</th>
                            <th>Actiuni</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
if($_GET['app']!="")
	app_project($_GET['app']);

if($_GET['rem']!="")
	rem_project($_GET['rem']);
$logs = get_pro($_GET['pag']);
foreach($logs as $log)
{
?>
<tr>
	<td><?php echo $log['ID']; ?></td>
	<td><?php echo show_user($log['IDutilizator']); ?></td>
	<td><?php echo get_project_data($log['IDproiect'])['nume'] ?></td>
	<td><?php echo $log['rank']; ?></td>
	<td><a href="dashboard.php?pagina=appproiecte&app=<?php echo $log['ID']; ?>">aproba</a> | <a href="dashboard.php?pagina=appproiecte&rem=<?php echo $log['ID']; ?>">respinge</a></td>
<?php
}
?>
                    </tbody>
                </table>

<?php if(pro_prev_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=appproiecte&pag=<?php echo ((int) $_GET['pag'] - 1); ?>" class="btn btn-primary" style="color:white;">Pagina anterioara</a>&nbsp;<?php } ?>
<?php if(pro_next_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=appproiecte&pag=<?php echo ((int) $_GET['pag'] + 1); ?>" class="btn btn-primary" style="color:white;">Pagina urmatoare</a><?php } ?>
