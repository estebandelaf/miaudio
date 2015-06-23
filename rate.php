<?php

	/*************************************************/
	/* MODULO PARA CALIFICAR LOS ARCHIVOS DE AUDIO   */
	/* Desarrollador: DeLaF www.delaf.tk             */
	/* Mail: esteban.delaf@gmail.com                 */
	/* Ultima version: 23/02/08                      */
	/*************************************************/

	if((!isset($_GET['voto']) && !isset($_POST['aid']) && !isset($miaudio)) || (isset($_GET['miaudio']) || isset($_POST['miaudio'])))
		header("location: ./error.php?error=4&file=".$_SERVER['PHP_SELF']);

	if(isset($_POST['voto']) && isset($_POST['aid'])) {
		require_once("./inc/config.inc.php");
		$sql_votos = $consultasSql->consulta("SELECT rate,nrate FROM ".$miaudio['mysql']['prefix']."files WHERE id = ".$consultasSql->proteger($_POST['aid']));
		$row_votos = mysql_fetch_array($sql_votos);
		$calificacion = (($row_votos['nrate']*$row_votos['rate']) + $consultasSql->proteger($_POST['voto'])) / ($row_votos['nrate'] + 1);
		$nvotos = $row_votos['nrate'] + 1;
		for($i=10;$i<=50;$i+=5) {
			if($calificacion>=($i-2.5) && $calificacion<($i+2.5))
				$calificacion = $i;
		}
		$consultasSql->consulta("UPDATE ".$miaudio['mysql']['prefix']."files SET rate = $calificacion, nrate = $nvotos WHERE id = ".$consultasSql->proteger($_POST['aid']));
		setcookie('miaudio_calif_voto',TRUE,($miaudio['realTime']+$miaudio['offsetRate'])); // PROBLEMA NO SE ESTA CREANDO LA COOKIE
		$consultasSql->cerrar();
		header("location: ./play.php?a=".$_POST['aid']);
	} else {
		$sql_votos = $consultasSql->consulta("SELECT rate,nrate FROM ".$miaudio['mysql']['prefix']."files WHERE id = ".$consultasSql->proteger($_GET['a']));
		$row_votos = mysql_fetch_array($sql_votos);
		echo $tab6,'Calificación: ';
		if($row_votos['rate'] && $row_votos['nrate']) {
			echo '<img src="./images/star_',$row_votos['rate'],'.gif" alt="" /> ',$row_votos['nrate'],' voto'; if($row_votos['nrate']>1) echo 's',$eol;
		} else echo 'aún sin calificar. ',$eol;
		if(!isset($_COOKIE['miaudio_calif_voto'])) {
			echo $tab6,'<form action="./rate.php" method="post">',$eol;
			echo $tab7,'<div class="rate">',$eol;
			echo $tab8,'<input type="hidden" name="aid" value="',$_GET['a'],'" />',$eol;
			echo $tab8,'<input type="radio" name="voto" value="10" onclick="this.form.submit()" />muy malo',$eol;
			echo $tab8,'<input type="radio" name="voto" value="20" onclick="this.form.submit()" />malo',$eol;
			echo $tab8,'<input type="radio" name="voto" value="30" onclick="this.form.submit()" />regular',$eol;
			echo $tab8,'<input type="radio" name="voto" value="40" onclick="this.form.submit()" />bueno',$eol;
			echo $tab8,'<input type="radio" name="voto" value="50" onclick="this.form.submit()" />muy bueno',$eol;
			echo $tab7,'</div>',$eol;
			echo $tab6,'</form>',$eol;
		} else
			echo $tab6,'<div>Debes esperar ',$miaudio['offsetRate'],' segundos antes de poder volver a votar.</div>',$eol;
	}

?>