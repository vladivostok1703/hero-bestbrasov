<?php
/* 
Name:		HeRo - pagina de logout
Description:	Pagina ce se ocupa de logout pentru platforma de HR
Author:		dragos.gaftoneanu@gmail.com
*/
include "intern-core/config.php"; //fisier principal de configurari si functii

session_destroy();

header("Location:index.php");
