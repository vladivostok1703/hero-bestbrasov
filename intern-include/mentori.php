<?php
if($_SESSION['intern-acces'] < 2)
{
	include "404.php";
	die();
}
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-cogs"></span></div>
            <div class="pagetitle">
		<h5>Adauga sau sterge mentorii pentru un utilizator</h5>
                <h1>Modifica mentorii utilizatorilor</h1>
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
                                <input type="hidden" name="pagina" value="mentori"><button class="btn btn-primary">Selecteaza</button>
                            </p>
                    </form>
                </div>

<?php
if(check_exists($_GET['utilizator']))
{
	$data = get_user_data($_GET['utilizator']);
?>
<div class="row">
		<div id="dashboard-left" class="col-md-6">
		<?php if($_POST['go']=="y"){echo add_mentor($_GET['utilizator'],$_POST['utilizator'],$_POST['csrf']); } ?>
                <h4 class="widgettitle">Adauga mentori utilizatorului <?php echo $data['nume']; ?></h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post">
			    <p>
                                <label>Alege mentorul</label>
                                <span class="field">
                                                                          <select name="utilizator" style="width:100%;">
					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI BABY</option>
<?php
$users = get_users_list(0);
foreach($users as $user)
{
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>

					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI ACTIVI</option>
<?php
$users = get_users_list(2);
foreach($users as $user)
{
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>

					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI CU DREPT DE VOT</option>
<?php
$users = get_users_list(4);
foreach($users as $user)
{
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>

					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI ALUMNI</option>
<?php
$users = get_users_list(5);
foreach($users as $user)
{
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>


					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI FORMER</option>
<?php
$users = get_users_list(3);
foreach($users as $user)
{
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>


					<option value=""></option>
					<option style="font-weight:bold;" value="">MEMBRI EXCLUSI</option>
<?php
$users = get_users_list(1);
foreach($users as $user)
{
		echo '<option value="'.$user['ID'].'">'.$user['nume'].'</option>';
}
 ?>
</select>
                                </span>
                            </p>
                            <p class="stdformbutton">
                                <input type="hidden" name="go" value="y"><input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><button class="btn btn-primary">Adauga</button>
                            </p>
                    </form>
                </div>
		</div>
		<div id="dashboard-left" class="col-md-6">
			<?php
				if(isset($_GET['del'])){ remove_mentor($_GET['del'],$_GET['utilizator']); }
			?>
			<h4 class="widgettitle"> Mentorii utilizatorului <?php echo $data['nume']; ?></h4>
                        <div class="widgetcontent">
                            <?php
				$mentors = get_mentors($_GET['utilizator']);
				if($mentors == "")
				{
					echo "<em>Niciun mentor nu a fost adaugat.</em>";
				}else{
					foreach($mentors as $mentor)
					{
						echo '<span class="iconfa-user"></span>&nbsp;&nbsp;' . show_user($mentor['IDmentor']) . ' <a href="dashboard.php?pagina=mentori&utilizator='.(int) $_GET['utilizator'].'&del='.$mentor['ID'].'"><span class="iconfa-remove"></span></a><br />';
					}
				}
				?>
                        </div>
		</div>
</div>
<?php
}
?>					
	
