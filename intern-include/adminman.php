<?php

if($_SESSION['intern-acces'] != 3)

{

	include "404.php";

	die();

}
?>       
        <div class="pageheader">
<div class="pageicon"><span class="iconfa-cogs"></span></div>
            <div class="pagetitle">
                <h5>Creeaza manual conturile oamenilor pentru HeRo</h5>
                <h1>Creare manuala a conturilor</h1>
            </div>
        </div><!--pageheader-->
        

        <div class="maincontent">
            <div class="maincontentinner">
          
<?php
if($_POST['csrf'] != "")
{
	echo mancreate($_POST);
}
?>
  
            <div class="widgetbox">
                <h4 class="widgettitle">Creare manuala a conturilor</h4>
<?php
				if(check_unauth() != '<em>Toti s-au conectat la platforma.</em>')
				{
?>
                    <form class="stdform stdform2" method="post" action="">
               <table class="table responsive">
                    <thead>
                        <tr>
                            <th>Adresa</th>
                            <th>Nume</th>
                        </tr>
                    </thead>
		    <tbody>
			<?php
				$unauth = explode(", ",check_unauth());
				foreach($unauth as $un)
				{
					?>
			                            <tr>
                               	<td><?php echo $un; ?></td>
                                <td>
                                    <input name="<?php echo "name_" . md5($un); ?>" placeholder="Prenume Nume" style="width:100%;">
                                </td>
                            </tr>
					<?php
				}
			?>
		     </tbody>
		</table>
                                                    
                            <p class="stdformbutton">
                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><input type="hidden" name="go" value="y"><button class="btn btn-primary">Salveaza</button>
                            </p>
                    </form>
                <?php } ?>
            </div>
