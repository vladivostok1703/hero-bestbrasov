<div class="pageheader">
    <div class="pageicon"><span class="iconfa-user"></span></div>
    <div class="pagetitle">
        <h5>Lista cu utilizatorii platformei</h5>
        <h1>Utilizatori</h1>
    </div>
</div>
<div class="maincontent">
    <div class="maincontentinner">

                <ul class="peoplegroup">
                    <li<?php if(!isset($_GET['status'])) {?> class="active"<?php } ?>><a href="dashboard.php?pagina=utilizatori">Toti (<?php echo get_members_status_count(""); ?>)</a></li>
                    <li<?php if($_GET['status']=="0"){ ?> class="active"<?php } ?>><a href="dashboard.php?pagina=utilizatori&status=0">Membri baby (<?php echo get_members_status_count("0"); ?>)</a></li>
                    <li<?php if($_GET['status']=="2"){ ?> class="active"<?php } ?>><a href="dashboard.php?pagina=utilizatori&status=2">Membri activi (<?php echo get_members_status_count("2"); ?>)</a></li>
                    <li<?php if($_GET['status']=="4"){ ?> class="active"<?php } ?>><a href="dashboard.php?pagina=utilizatori&status=4">Membri cu drept de vot (<?php echo get_members_status_count("4"); ?>)</a></li>
                    <li<?php if($_GET['status']=="5"){ ?> class="active"<?php } ?>><a href="dashboard.php?pagina=utilizatori&status=5">Membri alumni  (<?php echo get_members_status_count("5"); ?>)</a></li>
                    <li<?php if($_GET['status']=="3"){ ?> class="active"<?php } ?>><a href="dashboard.php?pagina=utilizatori&status=3">Membri former  (<?php echo get_members_status_count("3"); ?>)</a></li>
                    <li<?php if($_GET['status']=="1"){ ?> class="active"<?php } ?>><a href="dashboard.php?pagina=utilizatori&status=1">Membri exclusi  (<?php echo get_members_status_count("1"); ?>)</a></li>
                </ul>

        <div class="peoplelist">
            <?php 
		$users = get_users_list($_GET['status']);
		$nr = get_users_count();
		$i=0;	

		foreach($users as $user)
		{	
			$i++;
			if($i % 3 == 1)
				echo '<div class="row">';
		?>
            <div class="col-md-4">
                <div class="peoplewrapper">
                    <div class="thumb"><a href="dashboard.php?pagina=profil&id=<?php echo $user['ID'] ?>"><img src="<?php echo $user['poza']; ?>" alt="Poza lui <?php echo $user['nume']; ?>" style="height:80px;" border=0 /></a></div>
                    <div class="peopleinfo">
                        <h4>
                            <a href="dashboard.php?pagina=profil&id=<?php echo $user['ID'] ?>"><?php echo $user['nume']; ?></a>
                        </h4>
                        <ul>
                            <li><span>Statut:</span>
                                <?php echo get_lbg_status($user['statut']); ?>
                            </li>
                            <li><span>Email:</span>
                                <?php echo $user['email']; ?></li>
                            <li><span>Numar de telefon:</span>
                                <?php echo show_phone($user['nrtelefon']); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
		if($i % 3 == 0 || $i == $nr)
			echo '</div>';
		}
		?></div>
