<?php

	if(!isset($_GET['u']))
		header("location: ./error.php?error=4&file=".$_SERVER['PHP_SELF']);

	require("./inc/web1.inc.php");

	if($consultasSql->contar($miaudio['mysql']['prefix']."users","username",$consultasSql->proteger($_GET['u']))!="1") {
		header("location: ./error.php?error=2&u=".$_GET['u']);
	}
	
	$sql = $consultasSql->consulta("SELECT id,username,avatarFileName,about FROM ".$miaudio['mysql']['prefix']."users WHERE username = '".$consultasSql->proteger($_GET['u'])."'");
	$row = mysql_fetch_array($sql);
		
	if($row['avatarFileName']) $avatar = "./avatar.php?uid=".$row['id'];
	else $avatar = "./images/default_avatar.gif";
	
	echo $tab4,'<h2>Información sobre ',$row['username'],'</h2>',$eol;
	echo $tab4,'<div class="box">',$eol;
	echo $tab5,'<img src="',$avatar,'" alt="Avatar de ',$row['username'],'" class="right" />',$eol;
	echo $tab5,'<p>',formatearTxt($row['about']),'</p>',$eol;
	echo $tab5,'<div class="clear"></div>',$eol;
	echo $tab4,'</div>',$eol;
	
	echo $tab4,'<h3>Archivos de audio</h3>',$eol;
	echo $tab4,'<div class="box">',$eol;
	if($consultasSql->contar($miaudio['mysql']['prefix']."files","userId",$row['id'])) {
		echo $tab5,'<p>Los siguientes archivos de audio son los que ha subido este usuario:</p>',$eol;
		echo $tab5,'<ul>',$eol;
		$sql2 = $consultasSql->consulta("SELECT id,filename,size FROM ".$miaudio['mysql']['prefix']."files WHERE userId = '".$row['id']."' ORDER by date DESC");
		while ($row2 = mysql_fetch_array($sql2)) {
			$fileName = str_replace(".".end(explode("[/.]", $row2['filename'])),"",$row2['filename']);
			echo $tab6,'<li>',$eol;
			echo $tab7,$fileName,' (',round($row2['size']/1024,1),' KB)<br />',$eol;
			echo $tab7,'<a href="./play.php?a='.$row2['id'].'">[escuchar]</a>',$eol;
			echo $tab7,'<a href="./download.php?id=',$row2['id'],'">[bajar]</a>',$eol;
			echo $tab7,'<a href="#" onclick="agregarLista(',$row2['id'],')">[agregar a la lista]</a>',$eol;
			echo $tab6,'</li>',$eol;
		}
		echo $tab5,'</ul>',$eol;
	} else {
		echo $tab5,'<p>No existen archivos de audio subidos por este usuario.</p>',$eol;
	}
	echo $tab4,'</div>',$eol;

	if($consultasSql->contar($miaudio['mysql']['prefix']."groups","userId",$row['id'])) {
		echo $tab4,'<h3>Grupos</h3>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<p>',$row['username'],' ha creado los siguientes grupos de audio:</p>',$eol;
		echo $tab5,'<ul>',$eol;
		$sql2 = $consultasSql->consulta("SELECT id,name,shortName FROM ".$miaudio['mysql']['prefix']."groups WHERE userId = '".$row['id']."' ORDER by name ASC");
		while ($row2 = mysql_fetch_array($sql2)) {
			echo $tab7,'<li><a href="./g:'.$row2['shortName'].'">'.$row2['name'].'</a> (',$consultasSql->contar($miaudio['mysql']['prefix']."files","groupId",$row2['id']),') </li>',$eol;
		}
		echo $tab5,'</ul>',$eol;
		echo $tab4,'</div>',$eol;
	}
	
	if($consultasSql->contar($miaudio['mysql']['prefix']."playlists","userId",$row['id'])) {
		echo $tab4,'<h3>Listas de reproducción</h3>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<p>Listas de reproducción by ',$row['username'],':</p>',$eol;
		echo $tab5,'<ul>',$eol;
		$sql2 = $consultasSql->consulta("SELECT id,name,files FROM ".$miaudio['mysql']['prefix']."playlists WHERE userId = '".$row['id']."' ORDER by name ASC");
		while ($row2 = mysql_fetch_array($sql2)) {
			$nfiles = count(explode(" ",$row2['files']));
			echo $tab7,'<li><a href="./playlist.php?pl='.$row2['id'].'">'.$row2['name'].'</a> (',$nfiles,') </li>',$eol;
		}
		echo $tab5,'</ul>',$eol;
		echo $tab4,'</div>',$eol;
	}

	require("./inc/web2.inc.php");

?>