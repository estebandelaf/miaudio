<?php

        date_default_timezone_set('America/Santiago');

	// muestra/oculta errores PHP
	ini_set("display_errors", FALSE); // TRUE=mostrar o FALSE=ocultar
	error_reporting(0); // E_ALL=mostrar o 0=ocultar

	// datos del sitio web
	$miaudio['site']['name']	= "MiAuDiO";
	$miaudio['site']['desc']	= "Aloja tus archivos de sonido y compártelos con el mundo";
	$miaudio['site']['keywords']	= "canciones, musica, audio, hosting";
	$miaudio['site']['author']	= "DeLaF";

	// ubicacion del sitio web
	$miaudio['url']			= "http://mi.delaf.cl/miaudio";
	$miaudio['dir']			= dirname(dirname(__FILE__));

	// algunas variables
	$miaudio['offsetDate']		= 0; // desviacion en minutos respecto a la hora del servidor
	$miaudio['realTime']		= date("U") + $miaudio['offsetDate']*60; // calculo del tiempo real
	$miaudio['numUsers']['max']	= 2; // maximo numero de usuarios permitidos en el registro, con 0 infinitos
	$miaudio['filesOk']		= array("about","avatar","comments","discs","download","error","groups","login","logout","play","playlist","profile","rate","search","signup","terms","upload","user","userlist"); // archivos php en la carpeta raiz que usan config.inc.php
	$miaudio['lang']['default']	= "es"; // idioma por defecto
	$miaudio['protectSql']		= array("--",";","SELECT","OR","AND","LIKE","DROP","USE","TABLE","UNION","FROM","WHERE","LIMIT","MIN"); // sentencias SQL a eliminar de consultas, no se esta usando
	$miaudio['audioIndexNew']	= 10; // ultimos archivos de audio a mostrar en la portada
	$miaudio['offsetRate']		= 60; // tiempo en segundos para poder volver a calificar un archivo
	$miaudio['showTags']		= 50; // cantidad de tag mostrados en la busqueda, se mostraran los con mas archivos

	// variables para el upload archivos de audio
	$miaudio['maxFileSize']		= 20; // indicar en megabytes
	$miaudio['mimeType']		= array("audio/mp3","audio/mpeg","audio/mpeg3","audio/mpg","audio/x-mpeg","audio/x-mpeg-3"); // tipos mime permitidos
	$miaudio['extOk']		= array("mp3"); // extensiones permitidas

	$miaudio['timeLimit']		= 0; // tiempo maximo, en segundos, que puede demorar en subir un archivo (upload.php o avatar.php) con 0 es infinito

	// variables para el upload de avatares
	$miaudio['avatar']['maxFileSize']= 0.05; // indicar en megabytes
	$miaudio['avatar']['mimeType']	= array("image/png","image/x-png","image/jpeg","image/pjpeg","image/gif"); // tipos mime permitidos
	$miaudio['avatar']['extOk']	= array("jpg","jpeg","png","gif"); // extensiones permitidas

	// datos de la base de datos mysql
	$miaudio['mysql']['server']	= "localhost";
	$miaudio['mysql']['dataBase']	= "miaudio";
	$miaudio['mysql']['user']	= "miaudio";
	$miaudio['mysql']['password']	= "miaudio";
	$miaudio['mysql']['prefix']	= "";

	// salto de linea
	$eol = "\n\r";

	// tabulaciones
	$tab2 = "\t\t";
	$tab3 = "\t\t\t";
	$tab4 = "\t\t\t\t";
	$tab5 = "\t\t\t\t\t";
	$tab6 = "\t\t\t\t\t\t";
	$tab7 = "\t\t\t\t\t\t\t";
	$tab8 = "\t\t\t\t\t\t\t\t";
	$tab9 = "\t\t\t\t\t\t\t\t\t";

	// regiones de chile
	$miaudio['regChile'] = array(
		array(15,"Arica y Parinacota"),
		array(1,"Tarapacá"),
		array(2,"Antofagasta"),
		array(3,"Atacama"),
		array(4,"Coquimbo"),
		array(5,"Valparaíso"),
		array(6,"El Libertador General Bernardo O'Higgins"),
		array(7,"El Maule"),
		array(8,"El Bio bío"),
		array(9,"La Araucaní­a"),
		array(14,"Los Ríos"),
		array(10,"Los Lagos"),
		array(11,"Aisén del General Carlos Ibáñez del Campo"),
		array(12,"Magallanes y la Antártica Chilena"),
		array(13,"Metropolitana de Santiago")
	);

	require_once($miaudio['dir']."/inc/class.inc.php");
	require_once($miaudio['dir']."/inc/functions.inc.php");
	require_once($miaudio['dir']."/inc/actions.inc.php");

?>
