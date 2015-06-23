<?php

	/*************************************************/
	/* ARCHIVO DE DESCONECCION                       */
	/* Desarrollador: DeLaF www.delaf.tk             */
	/* Mail: esteban.delaf@gmail.com                 */
	/* Ultima version: 19-02-08                      */
	/*************************************************/

	require_once("./inc/config.inc.php");
	$consultasSql->cerrar();
	if(isset($_SESSION['userId']) && isset($_SESSION['hash']) && isset($_SESSION['username'])) {
		$_SESSION['userId'] = false;
		$_SESSION['hash'] = false;
		$_SESSION['username'] = false;
		session_destroy();
	}
	header("location: ./");

?>
