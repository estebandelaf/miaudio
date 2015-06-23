<?php

	require("./inc/web1.inc.php");
	
	echo $tab4,'<h2>Lista de usuarios</h2>',$eol;
	echo $tab4,'<div class="box">',$eol;
	echo $tab5,'<ul>',$eol;

	$sql = $consultasSql->consulta("SELECT id,username FROM ".$miaudio['mysql']['prefix']."users ORDER by username");
	while ($row = mysql_fetch_array($sql)) {
		echo $tab6,'<li><a href="./',$row['username'],'">',$row['username'],'</a> (',$consultasSql->contar($miaudio['mysql']['prefix']."files","userId",$row['id']),')</li>',$eol;
	}

	echo $tab5,'</ul>',$eol;
	echo $tab4,'</div>',$eol;


	require("./inc/web2.inc.php");

?>