<?php
include "intern-core/config.php";

if($_SESSION['intern-name'] == "")
{
	header("Location: index.php");
	die();
}
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="contacteLBGBrasov.csv"');
?>Name,Given Name,Additional Name,Family Name,Yomi Name,Given Name Yomi,Additional Name Yomi,Family Name Yomi,Name Prefix,Name Suffix,Initials,Nickname,Short Name,Maiden Name,Birthday,Gender,Location,Billing Information,Directory Server,Mileage,Occupation,Hobby,Sensitivity,Priority,Subject,Notes,Group Membership,E-mail 1 - Type,E-mail 1 - Value,Phone 1 - Type,Phone 1 - Value
<?php
$sql = $mysql->query("select * from utilizatori where statut=0 or statut=2 or statut=4 order by nume asc");
while($f = $sql->fetch_array(MYSQLI_ASSOC))
{
echo "BEST Brasov - " . $f['nume'] .",BEST Brasov - " . $f['nume'] .",,,,,,,,,,,,,,,,,,,,,,,,,* My Contacts,* Email,".$f['email'].",Mobile,".$f['nrtelefon']."\n";
}
