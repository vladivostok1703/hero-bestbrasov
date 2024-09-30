<?php
/* 
Name:		HeRo - pagina de login
Description:	Pagina ce se ocupa de login pentru platforma de HR
Author:		dragos.gaftoneanu@gmail.com
*/

include 'intern-core/config.php'; //fisier principal de configurari si functii

//fisiere pentru folosirea Google Auth
include 'intern-core/google/settings.php';
include 'intern-core/google/google-login-api.php';

if(isset($_GET['code']))
{
	try {
		$gapi = new GoogleLoginApi();
		$data = $gapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);
		$guser = $gapi->GetUserProfileInfo($data['access_token']);

		if(!check_auth($guser))
		{
			header("Location: index.php?page=error");
			exit();
		}

		check_user($guser);
		header("Location: dashboard.php");
		exit();
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit();
	}
}
