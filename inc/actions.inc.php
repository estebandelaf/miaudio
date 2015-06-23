<?php

	// conexion a mysql
	$consultasSql = new mysql(); // crea objeto para trabajar con la base de datos
	$consultasSql->conectar($miaudio['mysql']['server'],$miaudio['mysql']['dataBase'],$miaudio['mysql']['user'],$miaudio['mysql']['password']);

	// redireccionar al perfil de un usuario de ser necesario
	$parametros = recogerParametrosUrl(); // recibir usuario pasado por la url, se debe validar
	// verificar que no sea un archivo navegable el pasado por la url
	$redireccionar=1;
	for($i=0;$i<count($miaudio['filesOk']);$i++) {
		if($miaudio['filesOk'][$i].".php"==$parametros) {
			$redireccionar = 0;
			break;
		}
	}
	// si no es un archivo navegable verificar que el usuario exista y redireccionar
	if($redireccionar && $parametros) {
		if(preg_match('/^g:/',$parametros)) {
			$usuario_correcto = $consultasSql->contar($miaudio['mysql']['prefix']."groups", "shortName", $consultasSql->proteger(str_replace("g:","",$parametros)));
			//$consultasSql->cerrar(); mismo problema que en micd, si lo descomento no redirecciona
			if($usuario_correcto==1)
				header("location: ".$miaudio['url']."/groups.php?g=".str_replace("g:","",$parametros));
			else
				header("location: ".$miaudio['url']."/error.php?error=7&g=".str_replace("g:","",$parametros));
		} else {
			$usuario_correcto = $consultasSql->contar($miaudio['mysql']['prefix']."users", "username", $consultasSql->proteger($parametros));
			//$consultasSql->cerrar(); mismo problema que en micd, si lo descomento no redirecciona
			if($usuario_correcto==1)
				header("location: ".$miaudio['url']."/user.php?u=$parametros");
			else
				header("location: ".$miaudio['url']."/error.php?error=2&u=$parametros");
		}
	}
	
	// idioma del navegador del usuario
	$idioma_loaded = 0;
	$idiomas = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	foreach($idiomas as $lg) {
		if(file_exists($miaudio['dir']."/lang/".substr($lg,0,2).".php")) {
			require_once($miaudio['dir']."/lang/".substr($lg,0,2).".php");
			$idioma_loaded = 1;
			break;
		}
	}
	if(!$idioma_loaded) {
		if(file_exists($miaudio['dir']."/lang/".$miaudio['lang']['default'].".php"))
			require_once($micd['site']['dir']."/lang/".$miaudio['lang']['default'].".php");
		else {
			$consultasSql->cerrar();
			exit("Lo sentimos pero no se ha encontrado ninguna definici&oacute;n de idiomas y el sitio no puede ser mostrado.");
		}
	}

	// datos del usuario cliente ip y host
	if($_SERVER) {
		$realip = $_SERVER['REMOTE_ADDR'];
	} else {
		if(getenv('HTTP_X_FORWARDED_FOR')) {
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('HTTP_CLIENT_IP')) {
			$realip = getenv('HTTP_CLIENT_IP' );
		} else {
			$realip = getenv('REMOTE_ADDR');
		}
	}
	$ip = $realip; // establecer IP del visitante
	$host = gethostbyaddr($realip); // establecer host del visitante

	$miaudio['actualPage'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	// crear sesion del usuario
	session_name(md5("miaudio_".$ip."_".$host));
	session_start();
	header("cache-control: private");  // IE 6 Fix

	// verificar si el administrador esta logueado y leer datos del usuario
	$login = (isset($_SESSION['userId'])) ? $_SESSION['userId'] : 0;
	//if($login) require_once($miaudio['dir']."/inc/readuser.inc.php");
	
	// contar elementos en las tablas
	$miaudio['numUsers']['actual'] = $consultasSql->contar($miaudio['mysql']['prefix']."users");
	$miaudio['numFiles'] = $consultasSql->contar($miaudio['mysql']['prefix']."files");
	
	// de donde se viene, usado para evitar el envio de formularios desde otras web
	//$miaudio['pastUrl'] = $_SERVER['REFERER_URL'];
	//echo $miaudio['pastUrl'];


?>