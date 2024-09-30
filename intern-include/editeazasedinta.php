<?php
if($_SESSION['intern-acces'] == 1 || !check_meeting_exists($_GET['id']))
{
	include "404.php";
	die();
}
$data = get_meeting_data($_GET['id']);
?>       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-comments"></span></div>
            <div class="pagetitle">
                <h5>Editeaza o sedinta existenta</h5>
                <h1>Editeaza sedinta</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
			<?php
			if($_POST['submit']=="y")
			{
				echo edit_meeting($_POST['csrf'],$_POST['nume'],$_POST['data'],$_POST['categorie'],$_GET['id']);
				$data = get_meeting_data($_GET['id']);
			}
			?>
                <h4 class="widgettitle">Editeaza sedinta</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post">
						    <p>
                                <label>Numele sedintei</label>
                                <span class="field">
                                     <input type="text" class="form-control input-default" name="nume" value="<?php echo $data['nume']; ?>">
                                </span>
                            </p>
						    <p>
                                <label>Data sedintei</label>
                                <span class="field"><script type="text/javascript">jQuery(document).ready(function(){jQuery("#dp").datepicker({ dateFormat: 'yy-mm-dd' });});</script>
                                     <input type="text" class="form-control input-default" name="data" placeholder="AAAA-LL-ZZ" id="dp" value="<?php echo $data['data']; ?>">
                                </span>
                            </p>
						    <p>
                                <label>Categorie</label>
                                <span class="field">
                                     <select name="categorie" style="width:100%;">
									 <?php
									 $categorii = get_meeting_categories();
									 foreach($categorii as $categorie)
									 {
										if($data['categorie'] == $categorie['ID'])
											echo '<option value="'.$categorie['ID'].'" SELECTED>'.$categorie['nume'].'</option>';
										else
											echo '<option value="'.$categorie['ID'].'">'.$categorie['nume'].'</option>';
									 }
									 ?>
									 </select>
                                </span>
                            </p>
                            <p class="stdformbutton">
                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><input type="hidden" name="submit" value="y"><button class="btn btn-primary">Editeaza</button>
                            </p>
                    </form>
                </div>
				
				<?php
				if($_POST['submit2']=="y")
				{
					echo edit_people_meeting($_POST,$_GET['id']);
				}
				?>
                <h4 class="widgettitle">Participanti la sedinta</h4>
                <div class="widgetcontent nopadding" style="overflow-x: hidden;overflow-y: auto;height:535px;">
                    <form class="stdform stdform2" method="post">
<p><label><strong>MEMBRI BABY</strong></label></p>
				<?php
				$users = get_users_list(0);
				foreach($users as $user)
				{
						?>	<p>
                                <label><input type="checkbox" name="user_<?php echo $user['ID'];?>" value="p" <?php if(check_participation_meeting($user['ID'],$_GET['id'])){echo "checked";} ?>> Participat</label>
                                <span class="field">
                                    <?php echo $user['nume']; ?>
                                </span>
                            </p>
							<?php
					}
					?>
<p><label><strong>MEMBRI ACTIVI</strong></label></p>
				<?php
				$users = get_users_list(2);
				foreach($users as $user)
				{
						?>	<p>
                                <label><input type="checkbox" name="user_<?php echo $user['ID'];?>" value="p" <?php if(check_participation_meeting($user['ID'],$_GET['id'])){echo "checked";} ?>> Participat</label>
                                <span class="field">
                                    <?php echo $user['nume']; ?>
                                </span>
                            </p>
							<?php
					}
					?>
<p><label><strong>MEMBRI CU DREPT DE VOT</strong></label></p>
				<?php
				$users = get_users_list(4);
				foreach($users as $user)
				{
						?>	<p>
                                <label><input type="checkbox" name="user_<?php echo $user['ID'];?>" value="p" <?php if(check_participation_meeting($user['ID'],$_GET['id'])){echo "checked";} ?>> Participat</label>
                                <span class="field">
                                    <?php echo $user['nume']; ?>
                                </span>
                            </p>
							<?php
					}
					?>
<p><label><strong>MEMBRI ALUMNI</strong></label></p>
				<?php
				$users = get_users_list(5);
				foreach($users as $user)
				{
						?>	<p>
                                <label><input type="checkbox" name="user_<?php echo $user['ID'];?>" value="p" <?php if(check_participation_meeting($user['ID'],$_GET['id'])){echo "checked";} ?>> Participat</label>
                                <span class="field">
                                    <?php echo $user['nume']; ?>
                                </span>
                            </p>
							<?php
					}
					?>
<p><label><strong>MEMBRI FORMER</strong></label></p>
				<?php
				$users = get_users_list(3);
				foreach($users as $user)
				{
						?>	<p>
                                <label><input type="checkbox" name="user_<?php echo $user['ID'];?>" value="p" <?php if(check_participation_meeting($user['ID'],$_GET['id'])){echo "checked";} ?>> Participat</label>
                                <span class="field">
                                    <?php echo $user['nume']; ?>
                                </span>
                            </p>
							<?php
					}
					?>
<p><label><strong>MEMBRI EXCLUSI</strong></label></p>
				<?php
				$users = get_users_list(1);
				foreach($users as $user)
				{
						?>	<p>
                                <label><input type="checkbox" name="user_<?php echo $user['ID'];?>" value="p" <?php if(check_participation_meeting($user['ID'],$_GET['id'])){echo "checked";} ?>> Participat</label>
                                <span class="field">
                                    <?php echo $user['nume']; ?>
                                </span>
                            </p>
							<?php
					}
					?>

                            <p class="stdformbutton">
                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><input type="hidden" name="submit2" value="y"><button class="btn btn-primary">Editeaza</button>
                            </p>
                    </form>
                </div>
