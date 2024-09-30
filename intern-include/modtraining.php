<?php
if($_SESSION['intern-acces'] < 2)
{
	include "404.php";
	die();
}
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-cogs"></span></div>
            <div class="pagetitle">
		<h5>Modifica training-urile la care au participat utilizatorii</h5>
                <h1>Modifica training-urile utilizatorilor</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">

                <h4 class="widgettitle">Selecteaza utilizator</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="get">
			    <p>
                                <label>Utilizator</label>
                                <span class="field">
                                     <select name="utilizator" style="width:100%;">
					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI BABY</option>
<?php
$users = get_users_list(0);
foreach($users as $user)
{
	if($user['ID'] == $_GET['utilizator'])
		echo '<option value="'.$user['ID'].'" SELECTED>'.$user['nume'].'</option>';
	else
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>

					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI ACTIVI</option>
<?php
$users = get_users_list(2);
foreach($users as $user)
{
	if($user['ID'] == $_GET['utilizator'])
		echo '<option value="'.$user['ID'].'" SELECTED>'.$user['nume'].'</option>';
	else
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>

					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI CU DREPT DE VOT</option>
<?php
$users = get_users_list(4);
foreach($users as $user)
{
	if($user['ID'] == $_GET['utilizator'])
		echo '<option value="'.$user['ID'].'" SELECTED>'.$user['nume'].'</option>';
	else
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>

					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI ALUMNI</option>
<?php
$users = get_users_list(5);
foreach($users as $user)
{
	if($user['ID'] == $_GET['utilizator'])
		echo '<option value="'.$user['ID'].'" SELECTED>'.$user['nume'].'</option>';
	else
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>


					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI FORMER</option>
<?php
$users = get_users_list(3);
foreach($users as $user)
{
	if($user['ID'] == $_GET['utilizator'])
		echo '<option value="'.$user['ID'].'" SELECTED>'.$user['nume'].'</option>';
	else
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>


					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI EXCLUSI</option>
<?php
$users = get_users_list(1);
foreach($users as $user)
{
	if($user['ID'] == $_GET['utilizator'])
		echo '<option value="'.$user['ID'].'" SELECTED>'.$user['nume'].'</option>';
	else
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>
</select>
                                </span>
                            </p>
                            <p class="stdformbutton">
                                <input type="hidden" name="pagina" value="modtraining"><button class="btn btn-primary">Selecteaza</button>
                            </p>
                    </form>
                </div>

<?php
if(check_exists($_GET['utilizator']))
{
	if($_POST['go2']=="y")
	{
		echo save_trainings($_POST,$_GET['utilizator']);
	}
	$data = get_user_data($_GET['utilizator']);
?>
               <h4 class="widgettitle">Modifica training-urile utilizatorului <?php echo $data['nume']; ?></h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post">
<?php
$categorii = get_training_categories();
foreach($categorii as $categorie)
{
?>
	<p><script type="text/javascript">jQuery(document).ready(function(){jQuery("#dp_<?php echo $categorie['ID']; ?>").datepicker({ dateFormat: 'yy-mm-dd' });});</script>
		<label><?php echo $categorie['nume']; ?></label>
		<span class="field checkbox"> <input type="checkbox" name="training_<?php echo $categorie['ID'];?>" value="p"<?php if(check_training($categorie['ID'],$_GET['utilizator'])){echo " checked";} ?>> Participat 
		<input id="dp_<?php echo $categorie['ID']; ?>" type="text" placeholder="AAAA-LL-ZZ" name="dp_<?php echo $categorie['ID']; ?>" class="form-control" style="width:100px;" value="<?php echo get_trn_date($_GET['utilizator'],$categorie['ID']); ?>" />
		</span>
	</p>
<?php
}
?>
                            <p class="stdformbutton">
                                <input type="hidden" name="go2" value="y"><input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><button class="btn btn-primary">Modifica</button>
                            </p>
		    </form>
		</div>
<?php
}
?>
