<?php
/* Configurare baza de date */
$server = "localhost";
$username = "bestbras_intern";
$password = "usF[n_O2b72=";
$database = "bestbras_intern";
$mysql = mysqli_connect($server,$username,$password,$database) or die("Eroare: Conexiunea la baza de date a esuat.");

$act = 60; //nr de minute considerate pentru ultima activitate
$ver = "1.1"; //versiunea platformei
$elem = "10"; //elemente pe pagina
date_default_timezone_set("Europe/Bucharest");

/* Functii */
session_start();

function check_auth($data)
{
	global $mysql;
	
	if($mysql->query("select * from autorizari where email='".htmlentities($data['emails'][0]['value'],ENT_QUOTES)."'")->num_rows > 0)
		return true;
	
	return false;
}

function add_log($mesaj)
{
	global $mysql;

	$mesaj = htmlentities($mesaj,ENT_QUOTES);

	$mysql->query("insert into loguri(text,timestamp,autor) values ('$mesaj','".time()."','".$_SESSION['intern-id']."')");
}

function check_user($data)
{
	global $mysql;
	
	$sql = $mysql->query("select ID from utilizatori where email='".htmlentities($data['emails'][0]['value'],ENT_QUOTES)."'");
	if($sql->num_rows==0)
	{
		$mysql->query("insert into utilizatori(nume,email,poza,datanasterii,acces) values ('".htmlentities($data['displayName'],ENT_QUOTES)."','".htmlentities($data['emails'][0]['value'],ENT_QUOTES)."','".explode("?",htmlentities($data['image']['url'],ENT_QUOTES))[0]."','".htmlentities($data['birthday'],ENT_QUOTES)."','1')");
	}else{
		$mysql->query("update utilizatori set poza='".explode("?",htmlentities($data['image']['url'],ENT_QUOTES))[0]."' where email='".htmlentities($data['emails'][0]['value'],ENT_QUOTES)."'");
	}
	
	$info = $mysql->query("select * from utilizatori where email='".htmlentities($data['emails'][0]['value'],ENT_QUOTES)."'")->fetch_array(MYSQLI_ASSOC);

	$_SESSION['intern-id'] = $info['ID'];
	$_SESSION['intern-name'] = $info['nume'];
	$_SESSION['intern-email'] = $info['email'];
	$_SESSION['intern-poza'] = $info['poza'];
	$_SESSION['intern-dn'] = $info['datanasterii'];
	$_SESSION['intern-tel'] = $info['nrtelefon'];
	$_SESSION['intern-acces'] = $info['acces'];
	$_SESSION['intern-databest'] = $info['databest'];
	$_SESSION['intern-generatie'] = $info['numelegeneratiei'];
	$_SESSION['intern-csrf'] = md5($info['ID'] . md5(time()) . md5(rand(1000,5000)));
}

function update_activity()
{
	global $mysql;
	
	$mysql->query("update utilizatori set activitate=".time()." where ID=" . $_SESSION['intern-id']);
}

function return_time($timestamp)
{
	$mins = time() - $timestamp;
	if($mins == 0)
	{
		$mins = "adineauri";
	}elseif($mins == 1)
	{
		$mins = "acum o secunda";
	}elseif($mins == 13){
		$mins = "acum 12+1 secunde";
	}elseif($mins < 20)
	{
		$mins = "acum $mins secunde";
	}elseif($mins < 60)
	{
		$mins = "acum $mins de secunde";
	}elseif($mins < 120)
	{
		$mins = "acum un minut";
	}elseif($mins < 1200)
	{
		$mins = floor($mins / 60);
		if($mins == 13)
			$mins = "acum 12+1 minute";
		else
			$mins = "acum $mins minute";	
	}elseif($mins < 3600){
		$mins = floor($mins / 60);
		$mins = "acum $mins de minute";
	}elseif($mins < 7200){
		$mins = "acum o ora";
	}elseif($mins < 72000){
		$mins = floor($mins / 3600);
		if($mins == 13)
			$mins = "acum 12+1 ore";
		else
			$mins = "acum $mins ore";
	}elseif($mins < 86400){
		$mins = floor($mins / 3600);
		$mins = "acum $mins de ore";
	}else{
		$mins = date("j.m.Y",$timestamp) . " la " . date("H:i:s",$timestamp);
	}
	return $mins;
}

function count_online_users()
{
	global $mysql, $act;
	
	return $mysql->query("select ID from utilizatori where activitate >=" . (time() - $act * 60))->num_rows;
}

function online_users()
{
	global $mysql, $act;
	
	$query = $mysql->query("select ID,nume,poza,activitate from utilizatori where activitate >=" . (time() - $act * 60) . " order by activitate desc");
	$users = array();
	while($f = $query->fetch_array(MYSQLI_ASSOC))
	{
		$users[] = $f;
	}
	return $users;
}

function get_birthdays($timestamp)
{
	global $mysql;
	
	$query = $mysql->query("select utilizatori.ID, utilizatori.nume, utilizatori.datanasterii from utilizatori, autorizari where utilizatori.email = autorizari.email and utilizatori.datanasterii like '%-".date("m-d",$timestamp)."%' order by utilizatori.nume asc");
	if($query->num_rows == 0)
	{
		return "<em>Nu exista niciun sarbatorit.</em>";
	}else{
		$people = array();
		while($f = $query->fetch_array(MYSQLI_ASSOC))
		{
			$people[] = '<a href="dashboard.php?pagina=profil&id='.$f['ID'].'">' . $f['nume'] . "</a> (".(date("Y",$timestamp) - explode("-",$f["datanasterii"])[0]).")";
		}
		return implode(", ",$people);
	}
}

function cdate($date)
{
	$date = explode(".",$date);
	if($date[1] == "01")
		return $date[0] . " ianuarie " . $date[2];
	elseif($date[1] == "02")
		return $date[0] . " februarie " . $date[2];
	elseif($date[1] == "03")
		return $date[0] . " martie " . $date[2];
	elseif($date[1] == "04")
		return $date[0] . " aprilie " . $date[2];
	elseif($date[1] == "05")
		return $date[0] . " mai " . $date[2];
	elseif($date[1] == "06")
		return $date[0] . " iunie " . $date[2];
	elseif($date[1] == "07")
		return $date[0] . " iulie " . $date[2];
	elseif($date[1] == "08")
		return $date[0] . " august " . $date[2];
	elseif($date[1] == "09")
		return $date[0] . " septembrie " . $date[2];
	elseif($date[1] == "10")
		return $date[0] . " octombrie " . $date[2];
	elseif($date[1] == "11")
		return $date[0] . " noiembrie " . $date[2];
	elseif($date[1] == "12")
		return $date[0] . " decembrie " . $date[2];
}

function ddate($date)
{
	$date = explode("-",$date);
	if($date[1] == "01")
		return $date[2] . " ianuarie " . $date[0];
	elseif($date[1] == "02")
		return $date[2] . " februarie " . $date[0];
	elseif($date[1] == "03")
		return $date[2] . " martie " . $date[0];
	elseif($date[1] == "04")
		return $date[2] . " aprilie " . $date[0];
	elseif($date[1] == "05")
		return $date[2] . " mai " . $date[0];
	elseif($date[1] == "06")
		return $date[2] . " iunie " . $date[0];
	elseif($date[1] == "07")
		return $date[2] . " iulie " . $date[0];
	elseif($date[1] == "08")
		return $date[2] . " august " . $date[0];
	elseif($date[1] == "09")
		return $date[2] . " septembrie " . $date[0];
	elseif($date[1] == "10")
		return $date[2] . " octombrie " . $date[0];
	elseif($date[1] == "11")
		return $date[2] . " noiembrie " . $date[0];
	elseif($date[1] == "12")
		return $date[2] . " decembrie " . $date[0];
}

function edate($date)
{
	$date = explode("-",$date);
	return $date[2] . "." . $date[1] . "." . $date[0];
}

function save_settings_data($phone,$birthday,$csrf)
{
	global $mysql;
	$birthday = htmlentities($birthday,ENT_QUOTES);
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($phone == "" || $birthday == "")
		return '<div class="alert alert-danger">Datele nu au fost completate integral.</div>';
	
	if(strlen($phone) != 10 && !preg_match('/^[0-9]*$/', $phone) && $phone[0] != "0" && $phone[1] != "7")
		return '<div class="alert alert-danger">Numarul de telefon este gresit.</div>';
	
	if(strlen($birthday) != 10 && !stristr($birthday,"-"))
		return '<div class="alert alert-danger">Data nasterii este gresita.</div>';
	
	if(!checkdate(@explode("-",$birthday)[1],@explode("-",$birthday)[2],@explode("-",$birthday)[0]))
		return '<div class="alert alert-danger">Data nasterii este gresita.</div>';
	
	$mysql->query("update utilizatori set datanasterii='$birthday',nrtelefon='$phone' where ID='".$_SESSION['intern-id']."'");
	$_SESSION['intern-dn'] = $birthday;
	$_SESSION['intern-tel'] = $phone;
	return '<div class="alert alert-success">Datele au fost modificate.</div>';
}

function save_best_settings_data($databest,$generation,$csrf)
{
	global $mysql;
	$databest = htmlentities($databest,ENT_QUOTES);
	$generation = htmlentities($generation,ENT_QUOTES);
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($databest == "" || $generation == "")
		return '<div class="alert alert-danger">Datele nu au fost completate integral.</div>';
	
	$mysql->query("update utilizatori set databest='$databest', numelegeneratiei='$generation' where ID='".$_SESSION['intern-id']."'");
	$_SESSION['intern-databest'] = $databest;
	$_SESSION['intern-generatie'] = $generation;
	return '<div class="alert alert-success">Datele au fost modificate.</div>';
}

function check_exists($id)
{
	global $mysql;
	$id = (int) $id;
	
	return $mysql->query("select ID from utilizatori where ID=$id")->num_rows;
}

function get_user_data($id)
{
	global $mysql;
	$id = (int) $id;
	
	return $mysql->query("select * from utilizatori where ID=$id")->fetch_array(MYSQLI_ASSOC);
}

function get_rank($rank)
{
	if($rank == 1)
		return "Utilizator";
	
	if($rank == 2)
		return "Responsabil HR";

	if($rank == 3)
		return "Administrator";
}

function check_auth_by_email($email)
{
	global $mysql;
	
	return $mysql->query("select ID from autorizari where email='".htmlentities($email,ENT_QUOTES)."'")->num_rows;
}

function show_phone($phone)
{
	if($phone != "")
		return $phone[0] . $phone[1] . $phone[2] . $phone[3] . " " . $phone[4] . $phone[5] . $phone[6] . " " . $phone[7] . $phone[8] . $phone[9];
	else
		return "<em>Nesetat</em>";
}

function get_users_list($status)
{
	global $mysql;
	if(!isset($status))
		$query = "select * from utilizatori order by nume asc";
	else
		$query = "select * from utilizatori where statut=" . (int) $status . " order by nume asc";

	$users = array();
	$sql = $mysql->query($query);
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
	{
		$users[] = $f;
	}
	return $users;
}

function get_users_l()
{
	global $mysql;
	$query = "select * from utilizatori order by nume asc";

	$users = array();
	$sql = $mysql->query($query);
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
	{
		$users[] = $f;
	}
	return $users;
}

function get_users_count()
{
	global $mysql;

	return $mysql->query("select ID from utilizatori")->num_rows;
}

function get_lbg_status($status)
{
	$status = (int) $status;

	if($status == 0)
		return "Membru Baby";

	if($status == 1)
		return "Membru Exclus";

	if($status == 2)
		return "Membru Activ";

	if($status == 3)
		return "Membru Former";

	if($status == 4)
		return "Membru cu Drept de Vot";

	if($status == 5)
		return "Membru Alumni";
}

function get_members_status_count($status)
{
	global $mysql;

	if($status == "")
		$query = "select * from utilizatori";
	else
		$query = "select * from utilizatori where statut=" . (int) $status;

	return $mysql->query($query)->num_rows;
}

function get_full_url()
{
	return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function get_mentors($id)
{
	global $mysql;
	$id = (int) $id;

	$sql = $mysql->query("select ID,IDmentor from mentori where IDutilizator=$id");
	if($sql->num_rows)
	{
		$mentors = array();
		while($f = $sql->fetch_array(MYSQLI_ASSOC))
			$mentors[] = $f;
		return $mentors;
	}else{
		return "";
	}
}

function show_user($id)
{
	$data = get_user_data($id);
	return '<a href="dashboard.php?pagina=profil&id='.$id.'">' . $data['nume'] . '</a>';
}


function add_mentor($iduser,$idmentor,$csrf)
{
	global $mysql;

	$iduser = (int) $iduser;
	$idmentor = (int) $idmentor;

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($iduser == $idmentor)
		return '<div class="alert alert-danger">Nu poti adauga aceeasi persoana ca mentor.</div>';

	if($mysql->query("select * from mentori where IDutilizator=$iduser and IDmentor=$idmentor")->num_rows > 0)
		return '<div class="alert alert-danger">Persoana aceasta este deja mentor pentru acest utilizator.</div>';

	if(!check_exists($idmentor))
		return '<div class="alert alert-danger">Mentorul selectat nu exista.</div>';

	add_log("A fost adaugat mentorul ".show_user($idmentor)." pentru " . show_user($iduser) . ".");
	$mysql->query("insert into mentori(IDutilizator,IDmentor) values ('$iduser','$idmentor')");
	return '<div class="alert alert-success">Mentorul a fost adaugat.</div>';
}

function remove_mentor($id,$iduser)
{
	global $mysql;

	$id = (int) $id;
	$iduser = (int) $iduser;

	$query = $mysql->query("select IDmentor from mentori where ID=$id");
	if($query->num_rows > 0)
	{
		$f = $query->fetch_array(MYSQLI_ASSOC);
		add_log("A fost sters mentorul " . show_user($f['IDmentor']) . " pentru " . show_user($iduser) . ".");
		$mysql->query("delete from mentori where ID=$id and IDutilizator=$iduser");
	}
}

function calc_logs_pages()
{
	global $mysql,$elem;

	$entries = $mysql->query("select ID from loguri")->num_rows;
	return ceil($entries / $elem);
}

function get_logs($page)
{
	global $mysql,$elem;

	if($page < 0)
		$page = 0;

	if($page > calc_logs_pages() - 1)
		$page = 0;

	$limit = $elem * $page;
	$sql = $mysql->query("select * from loguri order by ID desc limit $limit,$elem");
	$logs = array();
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$logs[] = $f;

	return $logs;
}

function log_next_page($page)
{
	$pages = calc_logs_pages();
	$page = (int) $page;

	if($page < $pages - 1)
		return true;

	return false;
}

function log_prev_page($page)
{
	$pages = calc_logs_pages();
	$page = (int) $page;

	if($page >= 1)
		return true;

	return false;
}


function admin_save_user_settings($iduser,$phone,$birthday,$databest,$generation,$csrf)
{
	global $mysql;
	$birthday = htmlentities($birthday,ENT_QUOTES);
	$databest = htmlentities($databest,ENT_QUOTES);
	$generation = htmlentities($generation,ENT_QUOTES);
	$iduser = (int) $iduser;

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($phone == "" || $birthday == "")
		return '<div class="alert alert-danger">Datele nu au fost completate integral.</div>';
	
	if(strlen($phone) != 10 && !preg_match('/^[0-9]*$/', $phone) && $phone[0] != "0" && $phone[1] != "7")
		return '<div class="alert alert-danger">Numarul de telefon este gresit.</div>';
	
	if(strlen($birthday) != 10 && !stristr($birthday,"-"))
		return '<div class="alert alert-danger">Data nasterii este gresita.</div>';
	
	if(!checkdate(@explode("-",$birthday)[1],@explode("-",$birthday)[2],@explode("-",$birthday)[0]))
		return '<div class="alert alert-danger">Data nasterii este gresita.</div>';

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($databest == "" || $generation == "")
		return '<div class="alert alert-danger">Datele nu au fost completate integral.</div>';
	
	$mysql->query("update utilizatori set nrtelefon='$phone',datanasterii='$birthday',databest='$databest',numelegeneratiei='$generation' where ID=$iduser");
	add_log("A fost modificat profilul utilizatorului " . show_user($iduser) . ".");
	return '<div class="alert alert-success">Datele au fost modificate.</div>';
}

function admin_save_access($csrf,$iduser,$statut,$acces)
{
	global $mysql;

	$iduser = (int) $iduser;
	$statut = (int) $statut;
	$acces = (int) $acces;

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($statut < 0 || $statut > 5)
		return '<div class="alert alert-danger">Statutul selectat nu exista.</div>';

	if($acces < 1 || $acces > 3)
		return '<div class="alert alert-danger">Accesul selectat nu exista.</div>';

	$mysql->query("update utilizatori set statut=$statut, acces=$acces where ID=$iduser");
	add_log("Au fost modificate accesele utilizatorului " . show_user($iduser) . ".");
	return '<div class="alert alert-success">Accesele au fost modificate.</div>';
}

function get_training_categories()
{
	global $mysql;

	$categorii = array();
	$query = $mysql->query("select * from categoriitraining order by nume asc");

	if($query->num_rows)
	{
		while($f = $query->fetch_array(MYSQLI_ASSOC))
		{
			$categorii[] = $f;
		}
	}
	
	return $categorii;
}

function add_category($csrf,$nume)
{
	global $mysql;

	$nume = htmlentities($nume,ENT_QUOTES);

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($nume == "")
		return '<div class="alert alert-danger">Numele categoriei nu a fost completat.</div>';

	if($mysql->query("select ID from categoriitraining where nume='$nume'")->num_rows)
		return '<div class="alert alert-danger">Categoria exista deja.</div>';

	$mysql->query("insert into categoriitraining(nume) values ('$nume')");
	add_log("A fost adaugata categoria de training " . $nume . ".");
	return '<div class="alert alert-success">Categoria a fost adaugata.</div>';
}

function edit_category($csrf,$id,$nume)
{
	global $mysql;

	$nume = htmlentities($nume,ENT_QUOTES);

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($nume == "")
		return '<div class="alert alert-danger">Numele categoriei nu a fost completat.</div>';

	if($mysql->query("select ID from categoriitraining where ID=$id")->num_rows == 0)
		return '<div class="alert alert-danger">Categoria nu exista.</div>';

	$mysql->query("update categoriitraining set nume='$nume' where ID=$id");
	add_log("A fost editata categoria de training " . $nume . ".");
	return '<div class="alert alert-success">Categoria a fost editata.</div>';
}

function check_category_exists($id)
{
	global $mysql;

	$id = (int) $id;

	if($mysql->query("select ID from categoriitraining where ID=$id")->num_rows)
		return true;

	return false;
}

function get_category($id)
{
	global $mysql;

	$id = (int) $id;

	return $mysql->query("select * from categoriitraining where ID=$id")->fetch_array(MYSQLI_ASSOC);
}

function del_category($id)
{
	global $mysql;

	$id = (int) $id;

	$query = $mysql->query("select nume from categoriitraining where ID=$id");
	if($query->num_rows)
	{
		$f = $query->fetch_array(MYSQLI_ASSOC);
		add_log("A fost stearsa categoria de training " . $f['nume'] . ".");
		$mysql->query("delete from categoriitraining where ID=$id");
		$mysql->query("delete from traininguri where IDtraining=$id");
	}
}

function check_training($idcategorie,$iduser)
{
	global $mysql;

	$idcategorie = (int) $idcategorie;
	$iduser = (int) $iduser;

	if($mysql->query("select ID from traininguri where IDtraining=$idcategorie and IDutilizator=$iduser and aprobat=1")->num_rows)
		return true;

	return false;
}

function save_trainings($post,$iduser)
{
	global $mysql;
	$iduser = (int) $iduser;

	if($post['csrf'] != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	$categorii = get_training_categories();
	foreach($categorii as $categorie)
	{
		$data = htmlentities($_POST['dp_' . $categorie['ID']],ENT_QUOTES);
		if(checkdate(@explode("-",$_POST['dp_' . $categorie['ID']])[1],@explode("-",$_POST['dp_' . $categorie['ID']])[2],@explode("-",$_POST['dp_' . $categorie['ID']])[0]))
		{
			if($mysql->query("select * from traininguri where IDtraining=" . $categorie['ID'] . " and IDutilizator=" . $iduser)->num_rows && $post['training_' . $categorie['ID']] == "p")
				$mysql->query("update traininguri set aprobat=1,data='$data' where IDtraining=" . $categorie['ID'] . " and IDutilizator=" . $iduser);
			elseif($mysql->query("select * from traininguri where IDtraining=" . $categorie['ID'] . " and IDutilizator=" . $iduser . " and aprobat=1")->num_rows == 0 && $post['training_' . $categorie['ID']] == "p")
				$mysql->query("insert into traininguri(IDtraining,IDutilizator,aprobat,data) values (".$categorie['ID'].",".$iduser.",1,'$data')");
			elseif($mysql->query("select * from traininguri where IDtraining=" . $categorie['ID'] . " and IDutilizator=" . $iduser)->num_rows && $post['training_' . $categorie['ID']] != "p")
				$mysql->query("delete from traininguri where IDtraining=" . $categorie['ID'] . " and IDutilizator=" . $iduser);
		}
	}
	add_log("Au fost modificate training-urile utilizatorului " . show_user($iduser) . ".");
	return '<div class="alert alert-success">Training-urile au fost modificate.</div>';
}

function get_trainings_for_user($iduser)
{
	global $mysql;
	$iduser = (int) $iduser;

	$trainings = array();
	$sql = $mysql->query("select categoriitraining.nume,categoriitraining.ID from categoriitraining,traininguri where categoriitraining.ID=traininguri.IDtraining and traininguri.aprobat=1 and traininguri.IDutilizator=$iduser order by categoriitraining.nume asc");
	if($sql->num_rows)
	{
		while($f = $sql->fetch_array(MYSQLI_ASSOC))
			$trainings[] = '<a href="dashboard.php?pagina=trainings&show='.$f['ID'].'">' . $f['nume'] . '</a>';
		return implode(", ",$trainings);
	}else{
		return "<em>Niciun training nu a fost adaugat.</em>";
	}
}

function count_trainings_for_user($iduser)
{
	global $mysql;
	$iduser = (int) $iduser;

	$trainings = array();
	$sql = $mysql->query("select categoriitraining.nume from categoriitraining,traininguri where categoriitraining.ID=traininguri.IDtraining and traininguri.aprobat=1 and traininguri.IDutilizator=$iduser");
	if($sql->num_rows)
	{
		return $sql->num_rows;
	}else{
		return 0;
	}
}

function get_training_ranks()
{
	global $mysql;
	
	$ranks = array();
	$sql = $mysql->query("select IDutilizator, count(IDtraining) as c from traininguri where aprobat=1 group by IDutilizator order by c desc");
	echo $mysql->error;
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
	{
		$ranks[] = $f;
	}
	return $ranks;
}

function count_trainings($id)
{
	global $mysql;
	$id = (int) $id;
	
	return $mysql->query("select IDutilizator from traininguri where IDtraining=$id and aprobat=1")->num_rows;
}

function get_trainings($id)
{
	global $mysql;
	$id = (int) $id;
	
	$users = array();
	$sql = $mysql->query("select traininguri.IDutilizator,traininguri.data from traininguri,utilizatori where traininguri.IDtraining=$id and traininguri.aprobat=1 and traininguri.IDutilizator=utilizatori.ID order by utilizatori.nume");
	if($sql->num_rows)
	{
		while($f = $sql->fetch_array(MYSQLI_ASSOC))
			$users[] = $f;
		return $users;
	}else{
		return "";
	}
}

function check_participation($id)
{
	global $mysql;
	$id = (int) $id;
	
	if($mysql->query("select ID from traininguri where IDtraining=".$id." and IDutilizator=" . $_SESSION['intern-id'] . " and aprobat=1")->num_rows == 1)
		return 1;
	
	if($mysql->query("select ID from traininguri where IDtraining=".$id." and IDutilizator=" . $_SESSION['intern-id'] . " and aprobat=0")->num_rows == 1)
		return -1;

	return 0;
}

function add_training($id,$data)
{
	global $mysql;
	$id = (int) $id;
	$data = htmlentities($data,ENT_QUOTES);
	
	if(!checkdate(@explode("-",$data)[1],@explode("-",$data)[2],@explode("-",$data)[0]))
		return '';
	
	if(check_category_exists($id))
		if(check_participation($id) == 0)
			$mysql->query("insert into traininguri(IDtraining,IDutilizator,aprobat,data) values ($id,".$_SESSION['intern-id'].",0,'".$data."')");
}

function check_trainings_app()
{
	global $mysql;
	return $mysql->query("select * from traininguri where aprobat=0")->num_rows;
}

function calc_trn_pages()
{
	global $mysql,$elem;

	$entries = $mysql->query("select ID from traininguri where aprobat=0")->num_rows;
	return ceil($entries / $elem);
}

function get_trn($page)
{
	global $mysql,$elem;

	if($page < 0)
		$page = 0;

	if($page > calc_trn_pages() - 1)
		$page = 0;

	$limit = $elem * $page;
	$sql = $mysql->query("select * from traininguri where aprobat=0 order by ID desc limit $limit,$elem");
	$logs = array();
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$logs[] = $f;

	return $logs;
}

function trn_next_page($page)
{
	$pages = calc_trn_pages();
	$page = (int) $page;

	if($page < $pages - 1)
		return true;

	return false;
}

function trn_prev_page($page)
{
	$pages = calc_trn_pages();
	$page = (int) $page;

	if($page >= 1)
		return true;

	return false;
}

function app_trn($id)
{
	global $mysql;
	$id = (int) $id;
	
	$sql = $mysql->query("select IDutilizator,IDtraining from traininguri where ID=$id");
	if($sql->num_rows)
	{
		$f = $sql->fetch_array(MYSQLI_ASSOC);
		$mysql->query("update traininguri set aprobat=1 where ID=$id");
		add_log("A fost aprobat training-ul " . get_category($f['IDtraining'])['nume'] . " pentru ".show_user($f['IDutilizator']).".");
	}
}

function rem_trn($id)
{
	global $mysql;
	$id = (int) $id;
	
	$sql = $mysql->query("select IDutilizator,IDtraining from traininguri where ID=$id");
	if($sql->num_rows)
	{
		$f = $sql->fetch_array(MYSQLI_ASSOC);
		$mysql->query("delete from traininguri where ID=$id");
		add_log("A fost respins training-ul " . get_category($f['IDtraining'])['nume'] . " pentru ".show_user($f['IDutilizator']).".");
	}
}

function get_trn_date($user,$id)
{
	global $mysql;
	$id = (int) $id;
	$user = (int) $user;
	
	$sql = $mysql->query("select data from traininguri where IDutilizator=$user and IDtraining=$id");
	
	if($sql->num_rows)
		echo $sql->fetch_array(MYSQLI_ASSOC)['data'];
}

function check_catmeeting_exists($id)
{
	global $mysql;

	$id = (int) $id;

	if($mysql->query("select ID from sedintecategorii where ID=$id")->num_rows)
		return true;

	return false;
}

function get_catmeeting($id)
{
	global $mysql;

	$id = (int) $id;

	return $mysql->query("select * from sedintecategorii where ID=$id")->fetch_array(MYSQLI_ASSOC);
}

function edit_catmeeting($csrf,$id,$nume)
{
	global $mysql;

	$nume = htmlentities($nume,ENT_QUOTES);

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($nume == "")
		return '<div class="alert alert-danger">Numele categoriei nu a fost completat.</div>';

	if($mysql->query("select ID from sedintecategorii where ID=$id")->num_rows == 0)
		return '<div class="alert alert-danger">Categoria nu exista.</div>';

	$mysql->query("update sedintecategorii set nume='$nume' where ID=$id");
	add_log("A fost editata categoria de sedinte " . $nume . ".");
	return '<div class="alert alert-success">Categoria a fost editata.</div>';
}
 
function add_catmeeting($csrf,$nume)
{
	global $mysql;

	$nume = htmlentities($nume,ENT_QUOTES);

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($nume == "")
		return '<div class="alert alert-danger">Numele categoriei nu a fost completat.</div>';

	if($mysql->query("select ID from sedintecategorii where nume='$nume'")->num_rows)
		return '<div class="alert alert-danger">Categoria exista deja.</div>';

	$mysql->query("insert into sedintecategorii(nume) values ('$nume')");
	add_log("A fost adaugata categoria de sedinte " . $nume . ".");
	return '<div class="alert alert-success">Categoria a fost adaugata.</div>';
}

function del_catmeeting($id)
{
	global $mysql;

	$id = (int) $id;

	$query = $mysql->query("select nume from sedintecategorii where ID=$id");
	if($query->num_rows)
	{
		$f = $query->fetch_array(MYSQLI_ASSOC);
		add_log("A fost stearsa categoria de sedinte " . $f['nume'] . ".");
		$mysql->query("delete from sedintecategorii where ID=$id");
		$mysql->query("delete from sedinte where categorie=$id");
	}
}

function get_meeting_categories()
{
	global $mysql;

	$categorii = array();
	$query = $mysql->query("select * from sedintecategorii order by nume asc");

	if($query->num_rows)
	{
		while($f = $query->fetch_array(MYSQLI_ASSOC))
		{
			$categorii[] = $f;
		}
	}
	
	return $categorii;
}

function add_meeting($csrf,$nume,$data,$categorie)
{
	global $mysql;
	$nume = htmlentities($nume,ENT_QUOTES);
	$data = htmlentities($data,ENT_QUOTES);
	$categorie = (int) $categorie;
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	if(!checkdate(@explode("-",$data)[1],@explode("-",$data)[2],@explode("-",$data)[0]))
		return '<div class="alert alert-danger">Data este gresita.</div>';
	
	if($nume == "")
		return '<div class="alert alert-danger">Nu ai completat numele sedintei.</div>';
	
	if(!check_catmeeting_exists($categorie))
		return '<div class="alert alert-danger">Categoria selectata este incorecta.</div>';	
	
	$mysql->query("insert into sedinte(nume,data,anuntat_de,categorie) values ('$nume','$data',".$_SESSION['intern-id'].",".$categorie.")");
	add_log("A fost anuntata sedinta " . $nume . ".");
	return '<div class="alert alert-success">Sedinta a fost anuntata.</div>';
}

function get_meetings($page)
{
	global $mysql,$elem;

	if($page < 0)
		$page = 0;

	if($page > calc_meetings_pages() - 1)
		$page = 0;

	$limit = $elem * $page;
	$sql = $mysql->query("select * from sedinte order by data desc limit $limit,$elem");
	$logs = array();
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$logs[] = $f;

	return $logs;
}

function meetings_next_page($page)
{
	$pages = calc_meetings_pages();
	$page = (int) $page;

	if($page < $pages - 1)
		return true;

	return false;
}

function meetings_prev_page($page)
{
	$pages = calc_meetings_pages();
	$page = (int) $page;

	if($page >= 1)
		return true;

	return false;
}

function calc_meetings_pages()
{
	global $mysql,$elem;

	$entries = $mysql->query("select ID from sedinte")->num_rows;
	return ceil($entries / $elem);
}

function del_meeting($id)
{
	global $mysql;
	
	$id = (int) $id;
	add_log("A fost stearsa sedinta " . get_meeting_data($id)['nume'] . ".");
	$mysql->query("delete from sedintepax where IDsedinta=$id");
	$mysql->query("delete from sedinte where ID=$id");
}

function check_meeting_exists($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	if($mysql->query("select * from sedinte where ID=$id")->num_rows > 0)
		return true;
	
	return false;
}

function get_meeting_data($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	return $mysql->query("select * from sedinte where ID=$id")->fetch_array(MYSQLI_ASSOC);
}

function edit_meeting($csrf,$nume,$data,$categorie,$id)
{
	global $mysql;
	$nume = htmlentities($nume,ENT_QUOTES);
	$data = htmlentities($data,ENT_QUOTES);
	$categorie = (int) $categorie;
	$id = (int) $id;
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	if(!checkdate(@explode("-",$data)[1],@explode("-",$data)[2],@explode("-",$data)[0]))
		return '<div class="alert alert-danger">Data este gresita.</div>';
	
	if($nume == "")
		return '<div class="alert alert-danger">Nu ai completat numele sedintei.</div>';
	
	$mysql->query("update sedinte set nume='$nume',data='$data',categorie='$categorie' where ID=$id");
	add_log("A fost actualizata sedinta " . $nume . ".");
	return '<div class="alert alert-success">Sedinta a fost actualizata.</div>';
}

function check_participation_meeting($iduser,$idsedinta)
{
	global $mysql;
	$iduser = (int) $iduser;
	$idsedinta = (int) $idsedinta;
	
	if($mysql->query("select ID from sedintepax where IDsedinta=$idsedinta and IDutilizator=$iduser and aprobat=1")->num_rows > 0)
		return true;
	
	return false;
}

function edit_people_meeting($post,$id)
{
	global $mysql;
	
	$id = (int) $id;
	
	if(!check_meeting_exists($id))
		return '<div class="alert alert-danger">ID-ul sedintei nu este ok.</div>';
	
	if($post['csrf'] != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	$users = get_users_list();
	foreach($users as $user)
	{
		
		if($post['user_' . $user['ID']] == "p" && $mysql->query("select ID from sedintepax where IDsedinta=$id and IDutilizator=".$user['ID']." and aprobat=0")->num_rows == 1)
			$mysql->query("update sedintepax set aprobat=1 where IDutilizator=".$user['ID']." and IDsedinta=$id");
		elseif($post['user_' . $user['ID']] == "p" && $mysql->query("select ID from sedintepax where IDsedinta=$id and IDutilizator=".$user['ID']." and aprobat=0")->num_rows == 0 && !check_participation_meeting($user['ID'],$id))
			$mysql->query("insert into sedintepax(IDsedinta,IDutilizator,aprobat) values ($id,".$user['ID'].",1)");
		elseif($post['user_' . $user['ID']] != "p" && check_participation_meeting($user['ID'],$id))
			$mysql->query("delete from sedintepax where IDutilizator=".$user['ID']." and IDsedinta=$id");
	}
	add_log("A fost actualizata lista de participanti pentru sedinta " . get_meeting_data($id)['nume'] . ".");
}

function count_pax_meeting($id)
{
	global $mysql;
	
	$id = (int) $id;
	return $mysql->query("select IDutilizator from sedintepax where aprobat=1 and IDsedinta=$id")->num_rows;
}

function get_pax_meeting($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$users = array();
	$sql = $mysql->query("select IDutilizator from sedintepax where aprobat=1 and IDsedinta=$id");
	
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$users[] = $f;
	
	return $users;
}

function get_meetings_for_user($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$meetings = array();
	
	$sql = $mysql->query("select sedinte.nume,sedinte.data,sedinte.ID from sedinte,sedintepax where sedintepax.IDsedinta = sedinte.ID and sedintepax.aprobat=1 and sedintepax.IDutilizator=$id order by sedinte.data desc");
	if($sql->num_rows > 0)
	{
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$meetings[] = '<a href="dashboard.php?pagina=sedinte&show='.$f['ID'].'">' . $f['nume'] . "</a> (".edate($f['data']).")";
	
	return implode(", ",$meetings);
	}else{
		echo "<em>Nicio sedinta nu a fost adaugata.</em>";
	}
}

function count_meetings_for_user($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$meetings = array();
	
	$sql = $mysql->query("select sedinte.nume,sedinte.data,sedinte.ID from sedinte,sedintepax where sedintepax.IDsedinta = sedinte.ID and sedintepax.aprobat=1 and sedintepax.IDutilizator=$id order by sedinte.data desc");
	return $sql->num_rows;
}

function get_meeting_status_for_user($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	if($mysql->query("select ID from sedintepax where IDsedinta=$id and IDutilizator=" . $_SESSION['intern-id'] . " and aprobat=1")->num_rows == 1)
		return 1;
	
	if($mysql->query("select ID from sedintepax where IDsedinta=$id and IDutilizator=" . $_SESSION['intern-id'] . " and aprobat=0")->num_rows == 1)
		return -1;
	
	return 0;
}

function part_meeting($id)
{
	global $mysql;
	$id = (int) $id;
	
	if(!check_participation_meeting($_SESSION['intern-id'],$id) && $mysql->query("select * from sedintepax where IDutilizator=" . $_SESSION['intern-id'] . " and IDsedinta=$id")->num_rows == 0)
	{
		$mysql->query("insert into sedintepax(IDsedinta,IDutilizator,aprobat) values ($id,".$_SESSION['intern-id'].",0)");
	}
}

function check_meetings_app()
{
	global $mysql;
	
	return $mysql->query("select * from sedintepax where aprobat=0")->num_rows;
}

function app_meeting($id)
{
	global $mysql;
	$id = (int) $id;
	
	$sql = $mysql->query("select IDutilizator,IDsedinta from sedintepax where ID=$id");
	if($sql->num_rows)
	{
		$f = $sql->fetch_array(MYSQLI_ASSOC);
		$mysql->query("update sedintepax set aprobat=1 where ID=$id");
		add_log("A fost aprobata participarea lui " . show_user($f['IDutilizator'])  . " la sedinta " . get_meeting_data($f['IDsedinta'])['nume'] . ".");
	}
}

function rem_meeting($id)
{
	global $mysql;
	$id = (int) $id;
	
	$sql = $mysql->query("select IDutilizator,IDsedinta from sedintepax where ID=$id");
	if($sql->num_rows)
	{
		$f = $sql->fetch_array(MYSQLI_ASSOC);
		$mysql->query("delete from sedintepax where ID=$id");
				add_log("A fost respinsa participarea lui " . show_user($f['IDutilizator'])  . " la sedinta " . get_meeting_data($f['IDsedinta'])['nume'] . ".");
	}
}

function get_met($page)
{
	global $mysql,$elem;

	if($page < 0)
		$page = 0;

	if($page > calc_met_pages() - 1)
		$page = 0;

	$limit = $elem * $page;
	$sql = $mysql->query("select * from sedintepax where aprobat=0 order by ID desc limit $limit,$elem");
	$logs = array();
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$logs[] = $f;

	return $logs;
}

function calc_met_pages()
{
	global $mysql,$elem;

	$entries = $mysql->query("select ID from sedintepax where aprobat=0")->num_rows;
	return ceil($entries / $elem);
}

function met_next_page($page)
{
	$pages = calc_met_pages();
	$page = (int) $page;

	if($page < $pages - 1)
		return true;

	return false;
}

function met_prev_page($page)
{
	$pages = calc_met_pages();
	$page = (int) $page;

	if($page >= 1)
		return true;

	return false;
}

function check_catproject_exists($id)
{
	global $mysql;

	$id = (int) $id;

	if($mysql->query("select ID from proiectecategorii where ID=$id")->num_rows)
		return true;

	return false;
}

function get_catproject($id)
{
	global $mysql;

	$id = (int) $id;

	return $mysql->query("select * from proiectecategorii where ID=$id")->fetch_array(MYSQLI_ASSOC);
}

function edit_catproject($csrf,$id,$nume)
{
	global $mysql;

	$nume = htmlentities($nume,ENT_QUOTES);

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($nume == "")
		return '<div class="alert alert-danger">Numele categoriei nu a fost completat.</div>';

	if($mysql->query("select ID from proiectecategorii where ID=$id")->num_rows == 0)
		return '<div class="alert alert-danger">Categoria nu exista.</div>';

	$mysql->query("update proiectecategorii set nume='$nume' where ID=$id");
	add_log("A fost editata categoria de proiecte " . $nume . ".");
	return '<div class="alert alert-success">Categoria a fost editata.</div>';
}
 
function add_catproject($csrf,$nume)
{
	global $mysql;

	$nume = htmlentities($nume,ENT_QUOTES);

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	if($nume == "")
		return '<div class="alert alert-danger">Numele categoriei nu a fost completat.</div>';

	if($mysql->query("select ID from proiectecategorii where nume='$nume'")->num_rows)
		return '<div class="alert alert-danger">Categoria exista deja.</div>';

	$mysql->query("insert into proiectecategorii(nume) values ('$nume')");
	add_log("A fost adaugata categoria de proiecte " . $nume . ".");
	return '<div class="alert alert-success">Categoria a fost adaugata.</div>';
}

function del_catproject($id)
{
	global $mysql;

	$id = (int) $id;

	$query = $mysql->query("select nume from proiectecategorii where ID=$id");
	if($query->num_rows)
	{
		$f = $query->fetch_array(MYSQLI_ASSOC);
		add_log("A fost stearsa categoria de proiecte " . $f['nume'] . ".");
		$mysql->query("delete from proiectecategorii where ID=$id");
		$mysql->query("delete from proiecte where categorie=$id");
	}
}

function get_project_categories()
{
	global $mysql;

	$categorii = array();
	$query = $mysql->query("select * from proiectecategorii order by nume asc");

	if($query->num_rows)
	{
		while($f = $query->fetch_array(MYSQLI_ASSOC))
		{
			$categorii[] = $f;
		}
	}
	
	return $categorii;
}

function add_project($csrf,$nume,$descriere,$datai,$dataf,$categorie)
{
	global $mysql;
	$nume = htmlentities($nume,ENT_QUOTES);
	$descriere = htmlentities($descriere,ENT_QUOTES);
	$datai = htmlentities($datai,ENT_QUOTES);
	$dataf = htmlentities($dataf,ENT_QUOTES);
	$categorie = (int) $categorie;
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	if(!checkdate(@explode("-",$datai)[1],@explode("-",$datai)[2],@explode("-",$datai)[0]))
		return '<div class="alert alert-danger">Data este gresita.</div>';
	
	if(!checkdate(@explode("-",$dataf)[1],@explode("-",$dataf)[2],@explode("-",$dataf)[0]))
		return '<div class="alert alert-danger">Data este gresita.</div>';	
	
	if(mktime(@explode("-",$dataf)[1],@explode("-",$dataf)[2],@explode("-",$dataf)[0]) < mktime(@explode("-",$datai)[1],@explode("-",$datai)[2],@explode("-",$datai)[0]))
		return '<div class="alert alert-danger">Data de final nu poate fi mai mica decat data de inceput.</div>';	
	
	if($nume == "")
		return '<div class="alert alert-danger">Nu ai completat numele proiectului.</div>';
	
	if(!check_catproject_exists($categorie))
		return '<div class="alert alert-danger">Categoria nu exista.</div>';
	
	$mysql->query("insert into proiecte(nume,descriere,datai,dataf,categorie) values ('$nume','$descriere','$datai','$dataf',$categorie)");
	add_log("A fost anuntat proiectul " . $nume . ".");
	return '<div class="alert alert-success">Proiectul a fost anuntat.</div>';
}

function del_project($id)
{
	global $mysql;
	
	$id = (int) $id;
	add_log("A fost sters proiectul " . get_project_data($id)['nume'] . ".");
	$mysql->query("delete from proiectepax where IDproiect=$id");
	$mysql->query("delete from proiecte where ID=$id");
}

function get_project_data($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	return $mysql->query("select * from proiecte where ID=$id")->fetch_array(MYSQLI_ASSOC);
}

function get_projects($page)
{
	global $mysql,$elem;

	if($page < 0)
		$page = 0;

	if($page > calc_projects_pages() - 1)
		$page = 0;

	$limit = $elem * $page;
	$sql = $mysql->query("select * from proiecte order by dataf desc limit $limit,$elem");
	$logs = array();
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$logs[] = $f;

	return $logs;
}


function projects_next_page($page)
{
	$pages = calc_projects_pages();
	$page = (int) $page;

	if($page < $pages - 1)
		return true;

	return false;
}

function projects_prev_page($page)
{
	$pages = calc_projects_pages();
	$page = (int) $page;

	if($page >= 1)
		return true;

	return false;
}

function calc_projects_pages()
{
	global $mysql,$elem;

	$entries = $mysql->query("select ID from proiecte")->num_rows;
	return ceil($entries / $elem);
}

function project_category_data($id)
{
	global $mysql;
	
	$id = (int) $id;
	return $mysql->query("select * from proiectecategorii where ID=$id")->fetch_array(MYSQLI_ASSOC);
}

function project_exists($id)
{
	global $mysql;
	
	$id = (int) $id;
	if($mysql->query("select ID from proiecte where ID=$id")->num_rows > 0)
		return true;
	
	return false;
}

function edit_project($csrf,$nume,$descriere,$datai,$dataf,$categorie,$id)
{
	global $mysql;
	$nume = htmlentities($nume,ENT_QUOTES);
	$descriere = htmlentities($descriere,ENT_QUOTES);
	$datai = htmlentities($datai,ENT_QUOTES);
	$dataf = htmlentities($dataf,ENT_QUOTES);
	$categorie = (int) $categorie;
	$id = (int) $id;
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	if(!project_exists($id))
		return '<div class="alert alert-danger">Proiectul selectat este gresit.</div>'; 
	
	if(!checkdate(@explode("-",$datai)[1],@explode("-",$datai)[2],@explode("-",$datai)[0]))
		return '<div class="alert alert-danger">Data este gresita.</div>';
	
	if(!checkdate(@explode("-",$dataf)[1],@explode("-",$dataf)[2],@explode("-",$dataf)[0]))
		return '<div class="alert alert-danger">Data este gresita.</div>';	
	
	if(mktime(@explode("-",$dataf)[1],@explode("-",$dataf)[2],@explode("-",$dataf)[0]) < mktime(@explode("-",$datai)[1],@explode("-",$datai)[2],@explode("-",$datai)[0]))
		return '<div class="alert alert-danger">Data de final nu poate fi mai mica decat data de inceput.</div>';	
	
	if($nume == "")
		return '<div class="alert alert-danger">Nu ai completat numele proiectului.</div>';
	
	if(!check_catproject_exists($categorie))
		return '<div class="alert alert-danger">Categoria nu exista.</div>';
	
	$mysql->query("update proiecte set nume='$nume',descriere='$descriere',datai='$datai',dataf='$dataf',categorie=$categorie where ID=$id");
	add_log("A fost editat proiectul " . $nume . ".");
	return '<div class="alert alert-success">Proiectul a fost editat.</div>';
}

function add_pax_project($csrf,$id,$idp,$rank)
{
	global $mysql;
	$id = (int) $id;
	$idp = (int) $idp;
	$rank = htmlentities($rank,ENT_QUOTES);
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	if($rank == "")
		return '<div class="alert alert-danger">Responsabilitatea nu a fost completata.</div>';
	
	if(!check_exists($id))
		return '<div class="alert alert-danger">Utilizatorul nu exista.</div>';
	
	if(!project_exists($idp))
		return '<div class="alert alert-danger">Proiectul nu exista.</div>';
	
	if($mysql->query("select * from proiectepax where IDproiect=$idp and IDutilizator=$id and aprobat=1")->num_rows == 1)
		return '<div class="alert alert-danger">Utilizatorul este deja adaugat.</div>';
	
	if($mysql->query("select * from proiectepax where IDproiect=$idp and IDutilizator=$id and aprobat=1")->num_rows == 1)
		return '<div class="alert alert-danger">Utilizatorul este deja adaugat.</div>';
	
	if($mysql->query("select * from proiectepax where IDproiect=$idp and IDutilizator=$id and aprobat=0")->num_rows == 1)
		$mysql->query("update proiectepax set aprobat=1 where IDproiect=$idp and IDutilizator=$id");
	else
		$mysql->query("insert into proiectepax(IDproiect,IDutilizator,rank,aprobat) values ($idp,$id,'$rank',1)");
	
	add_log("A fost adaugat " . show_user($id) . " la proiectul " . get_project_data($idp)['nume'] . ".");
	return '<div class="alert alert-success">Proiectul a fost editat.</div>';
}

function get_pax_from_project($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$sql = $mysql->query("select proiectepax.IDutilizator,proiectepax.rank,proiectepax.ID from proiectepax,utilizatori where proiectepax.IDutilizator=utilizatori.ID and proiectepax.IDproiect=$id order by utilizatori.nume asc");
	if($sql->num_rows > 0)
	{
		$users = array();
		while($f = $sql->fetch_array(MYSQLI_ASSOC))
			$users[] = show_user($f['IDutilizator']) . ' ('.$f['rank'].') <a href="dashboard.php?pagina=editeazaproiect&id='.$id.'&del='.$f['ID'].'"><span class="iconfa-remove"></span></a>';
		
		return implode("<br />",$users);
	}else{
		echo "<em>Niciun participant nu a fost adaugat.</em>";
	}
}

function del_pax_from_project($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$sql = $mysql->query("select * from proiectepax where ID=$id");
	if($sql->num_rows > 0)
	{
		$f = $sql->fetch_array(MYSQLI_ASSOC);
		$mysql->query("delete from proiectepax where ID=$id");
		add_log("A fost sters " . show_user($f['IDutilizator']) . " de la proiectul " . get_project_data($f['IDproiect']) . ".");
	}
}

function get_projects_for_user($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$meetings = array();
	
	$sql = $mysql->query("select proiecte.nume, proiecte.ID, proiectepax.rank, proiecte.datai,proiecte.dataf from proiecte,proiectepax where proiectepax.aprobat=1 and proiecte.ID=proiectepax.IDproiect and proiectepax.IDutilizator=$id order by proiecte.dataf desc");
	if($sql->num_rows > 0)
	{
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
	{
		if($f['datai'] == $f['dataf'])
			$d = edate($f['datai']);
		else
			$d = edate($f['datai']) . ' - ' . edate($f['dataf']);
		$meetings[] = $f['rank'] . ' la <a href="dashboard.php?pagina=proiecte&show='.$f['ID'].'">' . $f['nume'] . '</a> ('.$d.')';
	}	return implode("<br />",$meetings);
	}else{
		echo "<em>Niciun proiect nu a fost adaugat.</em>";
	}
}

function count_projects_for_user($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$sql = $mysql->query("select proiecte.nume, proiecte.ID, proiectepax.rank, proiecte.datai,proiecte.dataf from proiecte,proiectepax where proiectepax.aprobat=1 and proiecte.ID=proiectepax.IDproiect and proiectepax.IDutilizator=$id order by proiecte.dataf desc");
	return $sql->num_rows;
}

function count_pax_project($id)
{
	global $mysql;
	
	$id = (int) $id;
	return $mysql->query("select IDutilizator from proiectepax where aprobat=1 and IDproiect=$id")->num_rows;
}

function get_pax_project($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$users = array();
	$sql = $mysql->query("select IDutilizator,rank from proiectepax where aprobat=1 and IDproiect=$id");
	
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$users[] = $f;
	
	return $users;
}

function check_project_exists($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	if($mysql->query("select * from proiecte where ID=$id")->num_rows > 0)
		return true;
	
	return false;
}

function part_project($id,$rank)
{
	global $mysql;
	$id = (int) $id;
	$rank = htmlentities($rank,ENT_QUOTES);
	
	if($rank != "" && !check_participation_project($_SESSION['intern-id'],$id) && $mysql->query("select * from proiectepax where IDutilizator=" . $_SESSION['intern-id'] . " and IDproiect=$id")->num_rows == 0)
	{
		$mysql->query("insert into proiectepax(IDproiect,IDutilizator,rank,aprobat) values ($id,".$_SESSION['intern-id'].",'$rank',0)");
	}
}

function check_participation_project($iduser,$idproiect)
{
	global $mysql;
	$iduser = (int) $iduser;
	$idproiect = (int) $idproiect;
	
	if($mysql->query("select ID from proiectepax where IDproiect=$idproiect and IDutilizator=$iduser and aprobat=1")->num_rows > 0)
		return true;
	
	return false;
}

function get_project_status_for_user($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	if($mysql->query("select ID from proiectepax where IDproiect=$id and IDutilizator=" . $_SESSION['intern-id'] . " and aprobat=1")->num_rows == 1)
		return 1;
	
	if($mysql->query("select ID from proiectepax where IDproiect=$id and IDutilizator=" . $_SESSION['intern-id'] . " and aprobat=0")->num_rows == 1)
		return -1;
	
	return 0;
}

function check_projects_app()
{
	global $mysql;
	
	return $mysql->query("select * from proiectepax where aprobat=0")->num_rows;
}

function app_project($id)
{
	global $mysql;
	$id = (int) $id;
	
	$sql = $mysql->query("select IDutilizator,IDproiect from proiectepax where ID=$id");
	if($sql->num_rows)
	{
		$f = $sql->fetch_array(MYSQLI_ASSOC);
		$mysql->query("update proiectepax set aprobat=1 where ID=$id");
		add_log("A fost aprobata participarea lui " . show_user($f['IDutilizator'])  . " la proiectul " . get_project_data($f['IDproiect'])['nume'] . ".");
	}
}

function rem_project($id)
{
	global $mysql;
	$id = (int) $id;
	
	$sql = $mysql->query("select IDutilizator,IDproiect from proiectepax where ID=$id");
	if($sql->num_rows)
	{
		$f = $sql->fetch_array(MYSQLI_ASSOC);
		$mysql->query("delete from proiectepax where ID=$id");
				add_log("A fost respinsa participarea lui " . show_user($f['IDutilizator'])  . " la proiectul " . get_project_data($f['IDproiect'])['nume'] . ".");
	}
}

function get_pro($page)
{
	global $mysql,$elem;

	if($page < 0)
		$page = 0;

	if($page > calc_pro_pages() - 1)
		$page = 0;

	$limit = $elem * $page;
	$sql = $mysql->query("select * from proiectepax where aprobat=0 order by ID desc limit $limit,$elem");
	$logs = array();
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$logs[] = $f;

	return $logs;
}

function calc_pro_pages()
{
	global $mysql,$elem;

	$entries = $mysql->query("select ID from proiectepax where aprobat=0")->num_rows;
	return ceil($entries / $elem);
}

function pro_next_page($page)
{
	$pages = calc_pro_pages();
	$page = (int) $page;

	if($page < $pages - 1)
		return true;

	return false;
}

function pro_prev_page($page)
{
	$pages = calc_pro_pages();
	$page = (int) $page;

	if($page >= 1)
		return true;

	return false;
}

function add_event($csrf,$nume,$descriere,$datai,$dataf)
{
	global $mysql;
	$nume = htmlentities($nume,ENT_QUOTES);
	$descriere = htmlentities($descriere,ENT_QUOTES);
	$datai = htmlentities($datai,ENT_QUOTES);
	$dataf = htmlentities($dataf,ENT_QUOTES);
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	if(!checkdate(@explode("-",$datai)[1],@explode("-",$datai)[2],@explode("-",$datai)[0]))
		return '<div class="alert alert-danger">Data este gresita.</div>';
	
	if(!checkdate(@explode("-",$dataf)[1],@explode("-",$dataf)[2],@explode("-",$dataf)[0]))
		return '<div class="alert alert-danger">Data este gresita.</div>';	
	
	if(mktime(@explode("-",$dataf)[1],@explode("-",$dataf)[2],@explode("-",$dataf)[0]) < mktime(@explode("-",$datai)[1],@explode("-",$datai)[2],@explode("-",$datai)[0]))
		return '<div class="alert alert-danger">Data de final nu poate fi mai mica decat data de inceput.</div>';	
	
	if($nume == "")
		return '<div class="alert alert-danger">Nu ai completat numele evenimentului.</div>';
	
	$mysql->query("insert into evenimente(nume,descriere,datai,dataf) values ('$nume','$descriere','$datai','$dataf')");
	add_log("A fost anuntat evenimentul " . $nume . ".");
	return '<div class="alert alert-success">Evenimentul a fost anuntat.</div>';
}

function del_event($id)
{
	global $mysql;
	
	$id = (int) $id;
	add_log("A fost sters evenimentul " . get_event_data($id)['nume'] . ".");
	$mysql->query("delete from evenimentepax where IDeveniment=$id");
	$mysql->query("delete from evenimente where ID=$id");
}

function get_event_data($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	return $mysql->query("select * from evenimente where ID=$id")->fetch_array(MYSQLI_ASSOC);
}

function get_events($page)
{
	global $mysql,$elem;

	if($page < 0)
		$page = 0;

	if($page > calc_events_pages() - 1)
		$page = 0;

	$limit = $elem * $page;
	$sql = $mysql->query("select * from evenimente order by dataf desc limit $limit,$elem");
	$logs = array();
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$logs[] = $f;

	return $logs;
}


function events_next_page($page)
{
	$pages = calc_events_pages();
	$page = (int) $page;

	if($page < $pages - 1)
		return true;

	return false;
}

function events_prev_page($page)
{
	$pages = calc_events_pages();
	$page = (int) $page;

	if($page >= 1)
		return true;

	return false;
}

function calc_events_pages()
{
	global $mysql,$elem;

	$entries = $mysql->query("select ID from evenimente")->num_rows;
	return ceil($entries / $elem);
}

function check_event_exists($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	if($mysql->query("select * from evenimente where ID=$id")->num_rows > 0)
		return true;
	
	return false;
}

function edit_event($csrf,$nume,$descriere,$datai,$dataf,$id)
{
	global $mysql;
	$nume = htmlentities($nume,ENT_QUOTES);
	$descriere = htmlentities($descriere,ENT_QUOTES);
	$datai = htmlentities($datai,ENT_QUOTES);
	$dataf = htmlentities($dataf,ENT_QUOTES);
	$id = (int) $id;
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	if(!checkdate(@explode("-",$datai)[1],@explode("-",$datai)[2],@explode("-",$datai)[0]))
		return '<div class="alert alert-danger">Data este gresita.</div>';
	
	if(!checkdate(@explode("-",$dataf)[1],@explode("-",$dataf)[2],@explode("-",$dataf)[0]))
		return '<div class="alert alert-danger">Data este gresita.</div>';	
	
	if(mktime(@explode("-",$dataf)[1],@explode("-",$dataf)[2],@explode("-",$dataf)[0]) < mktime(@explode("-",$datai)[1],@explode("-",$datai)[2],@explode("-",$datai)[0]))
		return '<div class="alert alert-danger">Data de final nu poate fi mai mica decat data de inceput.</div>';	
	
	if($nume == "")
		return '<div class="alert alert-danger">Nu ai completat numele evenimentului.</div>';
	
	if(!check_event_exists($id))
		return '<div class="alert alert-danger">Evenimentul selectat nu exista.</div>';
	
	$mysql->query("update evenimente set nume='$nume',descriere='$descriere',datai='$datai',dataf='$dataf' where ID=$id");
	add_log("A fost editat evenimentul " . $nume . ".");
	return '<div class="alert alert-success">Evenimentul a fost editat.</div>';
}

function add_pax_event($csrf,$id,$idp,$rank)
{
	global $mysql;
	$id = (int) $id;
	$idp = (int) $idp;
	$rank = htmlentities($rank,ENT_QUOTES);
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	if($rank == "")
		return '<div class="alert alert-danger">Responsabilitatea nu a fost completata.</div>';
	
	if(!check_exists($id))
		return '<div class="alert alert-danger">Utilizatorul nu exista.</div>';
	
	if(!check_event_exists($idp))
		return '<div class="alert alert-danger">Evenimentul nu exista.</div>';
	
	if($mysql->query("select * from evenimentepax where IDeveniment=$idp and IDutilizator=$id and aprobat=1")->num_rows == 1)
		return '<div class="alert alert-danger">Utilizatorul este deja adaugat.</div>';
	
	if($mysql->query("select * from evenimentepax where IDeveniment=$idp and IDutilizator=$id and aprobat=1")->num_rows == 1)
		return '<div class="alert alert-danger">Utilizatorul este deja adaugat.</div>';
	
	if($mysql->query("select * from evenimentepax where IDeveniment=$idp and IDutilizator=$id and aprobat=0")->num_rows == 1)
		$mysql->query("update evenimentepax set aprobat=1 where IDeveniment=$idp and IDutilizator=$id");
	else
		$mysql->query("insert into evenimentepax(IDeveniment,IDutilizator,rank,aprobat) values ($idp,$id,'$rank',1)");
	
	add_log("A fost adaugat " . show_user($id) . " la evenimentul " . get_event_data($idp)['nume'] . ".");
	return '<div class="alert alert-success">Evenimentul a fost editat.</div>';
}

function del_pax_from_event($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$sql = $mysql->query("select * from evenimentepax where ID=$id");
	if($sql->num_rows > 0)
	{
		$f = $sql->fetch_array(MYSQLI_ASSOC);
		$mysql->query("delete from evenimentepax where ID=$id");
		add_log("A fost sters " . show_user($f['IDutilizator']) . " de la evenimentul " . get_event_data($f['IDeveniment'])['nume'] . ".");
	}
}

function get_pax_from_event($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$sql = $mysql->query("select evenimentepax.IDutilizator,evenimentepax.rank,evenimentepax.ID from evenimentepax,utilizatori where evenimentepax.IDutilizator=utilizatori.ID and evenimentepax.IDeveniment=$id order by utilizatori.nume asc");
	if($sql->num_rows > 0)
	{
		$users = array();
		while($f = $sql->fetch_array(MYSQLI_ASSOC))
			$users[] = show_user($f['IDutilizator']) . ' ('.$f['rank'].') <a href="dashboard.php?pagina=editeazaeveniment&id='.$id.'&del='.$f['ID'].'"><span class="iconfa-remove"></span></a>';
		
		return implode("<br />",$users);
	}else{
		echo "<em>Niciun participant nu a fost adaugat.</em>";
	}
}

function get_events_for_user($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$meetings = array();
	
	$sql = $mysql->query("select evenimente.nume, evenimente.ID, evenimentepax.rank, evenimente.datai,evenimente.dataf from evenimente,evenimentepax where evenimentepax.aprobat=1 and evenimente.ID=evenimentepax.IDeveniment and evenimentepax.IDutilizator=$id order by evenimente.dataf desc");
	if($sql->num_rows > 0)
	{
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
	{
		if($f['datai'] == $f['dataf'])
			$d = edate($f['datai']);
		else
			$d = edate($f['datai']) . ' - ' . edate($f['dataf']);
		$meetings[] = $f['rank'] . ' la <a href="dashboard.php?pagina=evenimente&show='.$f['ID'].'">' . $f['nume'] . '</a> ('.$d.')';
	}	return implode("<br />",$meetings);
	}else{
		echo "<em>Niciun eveniment nu a fost adaugat.</em>";
	}
}

function count_events_for_user($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$sql = $mysql->query("select evenimente.nume, evenimente.ID, evenimentepax.rank, evenimente.datai,evenimente.dataf from evenimente,evenimentepax where evenimentepax.aprobat=1 and evenimente.ID=evenimentepax.IDeveniment and evenimentepax.IDutilizator=$id order by evenimente.dataf desc");
	return $sql->num_rows;
}

function count_pax_event($id)
{
	global $mysql;
	
	$id = (int) $id;
	return $mysql->query("select IDutilizator from evenimentepax where aprobat=1 and IDeveniment=$id")->num_rows;
}

function get_pax_event($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	$users = array();
	$sql = $mysql->query("select IDutilizator,rank from evenimentepax where aprobat=1 and IDeveniment=$id");
	
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$users[] = $f;
	
	return $users;
}

function part_event($id,$rank)
{
	global $mysql;
	$id = (int) $id;
	$rank = htmlentities($rank,ENT_QUOTES);
	
	if($rank != "" && !check_participation_event($_SESSION['intern-id'],$id) && $mysql->query("select * from evenimentepax where IDutilizator=" . $_SESSION['intern-id'] . " and IDeveniment=$id")->num_rows == 0)
	{
		$mysql->query("insert into evenimentepax(IDeveniment,IDutilizator,rank,aprobat) values ($id,".$_SESSION['intern-id'].",'$rank',0)");
	}
}

function check_participation_event($iduser,$idproiect)
{
	global $mysql;
	$iduser = (int) $iduser;
	$idproiect = (int) $idproiect;
	
	if($mysql->query("select ID from evenimentepax where IDeveniment=$idproiect and IDutilizator=$iduser and aprobat=1")->num_rows > 0)
		return true;
	
	return false;
}

function get_event_status_for_user($id)
{
	global $mysql;
	
	$id = (int) $id;
	
	if($mysql->query("select ID from evenimentepax where IDeveniment=$id and IDutilizator=" . $_SESSION['intern-id'] . " and aprobat=1")->num_rows == 1)
		return 1;
	
	if($mysql->query("select ID from evenimentepax where IDeveniment=$id and IDutilizator=" . $_SESSION['intern-id'] . " and aprobat=0")->num_rows == 1)
		return -1;
	
	return 0;
}

function check_events_app()
{
	global $mysql;
	
	return $mysql->query("select * from evenimentepax where aprobat=0")->num_rows;
}

function app_event($id)
{
	global $mysql;
	$id = (int) $id;
	
	$sql = $mysql->query("select IDutilizator,IDeveniment from evenimentepax where ID=$id");
	if($sql->num_rows)
	{
		$f = $sql->fetch_array(MYSQLI_ASSOC);
		$mysql->query("update evenimentepax set aprobat=1 where ID=$id");
		add_log("A fost aprobata participarea lui " . show_user($f['IDutilizator'])  . " la evenimentul " . get_event_data($f['IDeveniment'])['nume'] . ".");
	}
}

function rem_event($id)
{
	global $mysql;
	$id = (int) $id;
	
	$sql = $mysql->query("select IDutilizator,IDeveniment from evenimentepax where ID=$id");
	if($sql->num_rows)
	{
		$f = $sql->fetch_array(MYSQLI_ASSOC);
		$mysql->query("delete from evenimentepax where ID=$id");
		add_log("A fost respinsa participarea lui " . show_user($f['IDutilizator'])  . " la evenimentul " . get_event_data($f['IDeveniment'])['nume'] . ".");
	}
}

function get_eve($page)
{
	global $mysql,$elem;

	if($page < 0)
		$page = 0;

	if($page > calc_eve_pages() - 1)
		$page = 0;

	$limit = $elem * $page;
	$sql = $mysql->query("select * from evenimentepax where aprobat=0 order by ID desc limit $limit,$elem");
	$logs = array();
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
		$logs[] = $f;

	return $logs;
}

function calc_eve_pages()
{
	global $mysql,$elem;

	$entries = $mysql->query("select ID from evenimentepax where aprobat=0")->num_rows;
	return ceil($entries / $elem);
}

function eve_next_page($page)
{
	$pages = calc_eve_pages();
	$page = (int) $page;

	if($page < $pages - 1)
		return true;

	return false;
}

function eve_prev_page($page)
{
	$pages = calc_eve_pages();
	$page = (int) $page;

	if($page >= 1)
		return true;

	return false;
}

function ban_user($id)
{
	global $mysql;
	$id = (int) $id;
	
	if(check_exists($id))
	{
		$mysql->query("delete from autorizari where email='".get_user_data($id)['email']."'");
	}
}

function unban_user($id)
{
	global $mysql;
	$id = (int) $id;
	
	if(check_exists($id) && !check_auth_by_id($id))
	{
		$mysql->query("insert into autorizari(email) values ('".get_user_data($id)['email']."')");
	}
}

function check_auth_by_id($id)
{
	global $mysql;
	$id = (int) $id;
	
	if(check_exists($id))
		return $mysql->query("select ID from autorizari where email='".get_user_data($id)['email']."'")->num_rows;
}

function get_auth_emails()
{
	global $mysql;
	
	$query = $mysql->query("select email from autorizari order by email asc");
	$emails = array();
	while($f = $query->fetch_array(MYSQLI_ASSOC))
		$emails[] = $f['email'];
	
	return $emails;
}

function add_auth_email($csrf,$emails)
{
	global $mysql;
	$emails = array_unique(explode(",",htmlentities($emails,ENT_QUOTES)));
	
	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	if(count($emails) == 0)
		return '<div class="alert alert-danger">Nu ai adaugat nici un email.</div>';
	
	$sql = $mysql->query("select email from autorizari");
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
	{
		if(!in_array($f['email'],$emails))
			$mysql->query("delete from autorizari where email='".$f['email']."'");
	}
	
	foreach($emails as $email)
	{
		if($mysql->query("select ID from autorizari where email='".$email."'")->num_rows == 0)
			$mysql->query("insert into autorizari(email) values ('$email')");
	}
	
	return '<div class="alert alert-success">Autorizarile au fost modificate.</div>';
}

function check_unauth()
{
	global $mysql;
	
	$emails = array();
	$sql = $mysql->query("select t1.email from autorizari t1 left join utilizatori t2 on t2.email = t1.email where t2.email is null order by t1.email");
	
	if($sql->num_rows > 0)
	{
		while($f = $sql->fetch_array(MYSQLI_ASSOC))
			$emails[] = $f['email'];
		return implode(", ",$emails);
	}else{
		return "<em>Toti s-au conectat la platforma.</em>";
	}
}

function change_name($csrf,$id,$name)
{
	global $mysql;
	
	$name = htmlentities($name,ENT_QUOTES);

	if($csrf != $_SESSION['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';
	
	if($name == "")
		return '<div class="alert alert-danger">Nu ai completat campul de nume.</div>';
	
	if(!check_exists($id))
		return '<div class="alert alert-danger">Utilizatorul nu exista.</div>';
	
	$mysql->query("update utilizatori set nume='$name' where ID=$id");
	return '<div class="alert alert-success">Numele utilizatorului a fost schimbat.</div>';
}

function mancreate($post)
{
	global $mysql;

	$unauth = explode(", ",check_unauth());

	if($csrf != $post['intern-csrf'])
		return '<div class="alert alert-danger">Token-ul CSRF este gresit.</div>';

	foreach($unauth as $un)
	{
		if($post['name_' . md5($un)] != "" && $mysql->query("select * from utilizatori where email='$un'")->num_rows == 0 && $mysql->query("select * from autorizari where email='$un'")->num_rows == 1)
		{
			$mysql->query("insert into utilizatori(nume,email,poza,acces,statut) values ('".$post['name_' . md5($un)]."','$un','https://hero.bestbrasov.ro/intern-design/photo.jpg','1','0')");
		}
	}

	return '<div class="alert alert-success">Conturile au fost create.</div>';
}
