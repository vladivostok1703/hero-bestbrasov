<?php
if($_SESSION['intern-acces'] < 2)
{
	include "404.php";
	die();
}
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-cogs"></span></div>
            <div class="pagetitle">
		<h5>Modifica datele unui utilizator pe platforma</h5>
                <h1>Modifica datele utilizatorilor</h1>
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
                                <input type="hidden" name="pagina" value="moduseri"><button class="btn btn-primary">Selecteaza</button>
                            </p>
                    </form>
                </div>

<?php
if(check_exists($_GET['utilizator']))
{
	$data = get_user_data($_GET['utilizator']);

if($_SESSION['intern-acces'] == "3")
{
?>
		<?php if($_POST['go2'] == "y") { echo admin_save_access($_POST['csrf'],$_GET['utilizator'],$_POST['statut'],$_POST['acces']);$data = get_user_data($_GET['utilizator']); } ?>
               <h4 class="widgettitle">Modifica accesele utilizatorului <?php echo $data['nume']; ?></h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post">
			     <p>
                                <label>Statut in LBG</label>
                                <span class="field">
                                     	<select name="statut" style="width:100%;">
						<option value="0"<?php if($data['statut']==0) {echo " SELECTED";} ?>>Membru Baby</option>
						<option value="1"<?php if($data['statut']==1) {echo " SELECTED";} ?>>Membru Exclus</option>
						<option value="2"<?php if($data['statut']==2) {echo " SELECTED";} ?>>Membru Activ</option>
						<option value="3"<?php if($data['statut']==3) {echo " SELECTED";} ?>>Membru Former</option>
						<option value="4"<?php if($data['statut']==4) {echo " SELECTED";} ?>>Membru cu Drept de Vot</option>
						<option value="5"<?php if($data['statut']==5) {echo " SELECTED";} ?>>Membru Alumni</option>
					</select>
                                </span>
                            </p>
			     <p>
                                <label>Acces platforma</label>
                                <span class="field">
                                     	<select name="acces" style="width:100%;">
						<option value="1"<?php if($data['acces']==1) {echo " SELECTED";} ?>>Utilizator</option>
						<option value="2"<?php if($data['acces']==2) {echo " SELECTED";} ?>>Responsabil HR</option>
						<option value="3"<?php if($data['acces']==3) {echo " SELECTED";} ?>>Administrator</option>
					</select>
                                </span>
                            </p>
                            <p class="stdformbutton">
                                <input type="hidden" name="go2" value="y"><input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><button class="btn btn-primary">Modifica</button>
                            </p>
		    </form>
		</div>
<?php
}

			if($_POST['go3']=="y")
				echo change_name($_POST['csrf'],$_GET['utilizator'],$_POST['nume']);
?>

                <h4 class="widgettitle">Modifica numele utilizatorului <?php echo $data['nume']; ?></h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post">
			     <p>
                                <label>Nume</label>
                                <span class="field">
                                     <input name="nume" type="text" class="form-control input-default" value="<?php echo $data['nume']; ?>">
                                </span>
                            </p>
                            <p class="stdformbutton">
                                <input type="hidden" name="go3" value="y"><input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><button class="btn btn-primary">Modifica</button>
                            </p>
                    </form>
                </div>

		<?php if($_POST['go']=="y"){echo admin_save_user_settings($_GET['utilizator'],$_POST['telefon'],$_POST['datanasterii'],$_POST['databest'],$_POST['generatie'],$_POST['csrf']);$data = get_user_data($_GET['utilizator']); } ?>
                <h4 class="widgettitle">Modifica datele utilizatorului <?php echo $data['nume']; ?></h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post">
			     <p>
                                <label>Numar de telefon<br /><small>07XXXXXXXX</small></label>
                                <span class="field">
                                     <input name="telefon" type="text" class="form-control input-default"  value="<?php echo $data['nrtelefon']; ?>">
                                </span>
                            </p>
			    <p>
                                <label>Data nasterii<br /><small>AAAA-LL-ZZ</small></label>
                                <span class="field">
                                     <input name="datanasterii" type="text" class="form-control input-default"  value="<?php echo $data['datanasterii']; ?>">
                                </span>
                            </p>
		            <p>
                                <label>Cand a intrat in BEST<br /><small>luna si anul</small></label>
                                <span class="field">
                                     <input name="databest" type="text" class="form-control input-default"  value="<?php echo $data['databest']; ?>">
                                </span>
                            </p>
						    <p>
                                <label>Numele generatiei</label>
                                <span class="field">
                                     <input name="generatie" type="text" class="form-control input-default"  value="<?php echo $data['numelegeneratiei']; ?>">
                                </span>
                            </p>
                            <p class="stdformbutton">
                                <input type="hidden" name="go" value="y"><input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><button class="btn btn-primary">Modifica</button>
                            </p>
                    </form>
                </div>
<?php
}
?>					
	
