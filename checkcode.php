<?php

	require_once("./inc/functions.inc.php");

	$checkcode = rand(1000,9999);
	setcookie('miaudio_user_reg_ver',md5($checkcode));
	toImage($checkcode);

?>