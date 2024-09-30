<?php
if($_SESSION['intern-acces'] < 2)
{
	include "404.php";
	die();
}
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-cogs"></span></div>
            <div class="pagetitle">
                <h5>Activitate realizata de responsabilii HR si administratori</h5>
                <h1>Log-uri administrative</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">

                <h4 class="widgettitle">Log-uri administrative</h4>
                <table class="table responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Autor</th>
                            <th>Data</th>
                            <th>Activitate</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$logs = get_logs($_GET['pag']);
foreach($logs as $log)
{
?>
<tr>
	<td><?php echo $log['ID']; ?></td>
	<td><?php echo show_user($log['autor']); ?></td>
	<td><?php echo return_time($log['timestamp']); ?></td>
	<td><?php echo html_entity_decode($log['text'],ENT_QUOTES); ?></td>
<?php
}
?>
                    </tbody>
                </table>

<?php if(log_prev_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=logs&pag=<?php echo ((int) $_GET['pag'] - 1); ?>" class="btn btn-primary" style="color:white;">Pagina anterioara</a>&nbsp;<?php } ?>
<?php if(log_next_page($_GET['pag'])){ ?><a href="dashboard.php?pagina=logs&pag=<?php echo ((int) $_GET['pag'] + 1); ?>" class="btn btn-primary" style="color:white;">Pagina urmatoare</a><?php } ?>
