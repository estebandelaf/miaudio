<?php 

	if (isset($_GET['id'])) {
		require_once("./inc/config.inc.php");
		$sql = $consultasSql->consulta("SELECT * FROM ".$miaudio['mysql']['prefix']."files WHERE id='".$consultasSql->proteger($_GET['id'])."'");
		$row = mysql_fetch_array($sql);
		$consultasSql->cerrar();
		header("Content-type: ".$row['format']);
		header("Content-length: ".$row['size']);
		header("Content-Disposition: attachment; filename=\"".$row['fileName']."\"");
		echo $row['object'];
	} else {
		header("location: ./error.php?error=4&file=".$_SERVER['PHP_SELF']);
	}

?>