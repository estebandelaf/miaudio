<?php

	require("./inc/web1.inc.php");
	
	if(!isset($_GET['pl'])) {
		echo $tab4,'<h2>Listas de reproducción</h2>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<p>A continuación se muestran las listas creadas por nuestros usuarios:</p>',$eol;
		echo $tab5,'<ul>',$eol;
		$sql = $consultasSql->consulta("SELECT id,name,files,userId FROM ".$miaudio['mysql']['prefix']."playlists ORDER by name ASC");
		while ($row = mysql_fetch_array($sql)) {
			$sql2 = $consultasSql->consulta("SELECT username FROM ".$miaudio['mysql']['prefix']."users WHERE id = ".$row['userId']);
			$row2 = mysql_fetch_array($sql2);
			$nfiles = count(explode(" ",$row['files']));
			echo $tab6,'<li><a href="',$_SERVER['PHP_SELF'],'?pl=',$row['id'],'">',$row['name'],'</a> (',$nfiles,') by <a href="./',$row2['username'],'">',$row2['username'],'</a></li>',$eol;
		}
		echo $tab5,'</ul>',$eol;
		echo $tab4,'</div>',$eol;
	} else {
		$sql = $consultasSql->consulta("SELECT name,files FROM ".$miaudio['mysql']['prefix']."playlists WHERE id = ".$consultasSql->proteger($_GET['pl']));
		$row = mysql_fetch_array($sql);
		$playlist = explode(" ",$row['files']);
		
		// una forma de desordenar la lista de reproduccion para hacerla shuffle
		// usando microtime obtengo el ultimo digito antes de los 00
		// meto cada digito acompañando a la playlist y luego ordeno mediante ese digito
		foreach($playlist as $audio) {
			list($useg, $seg) = explode(" ", microtime());
			$playlistShuffle[] = array('file' => $audio, 'utime' => substr($useg,7,1));
		}
		// obtener una lista de columnas
		foreach ($playlistShuffle as $llave => $fila)
			$playlistShuffle_[$llave]  = $fila['utime'];
		// ordenar la matriz por orden alfabetico de tag
		array_multisort($playlistShuffle_, SORT_ASC, $playlistShuffle);
		
		// crear parametro a pasar al reproductor en flash
		$audiolist = "";
		foreach($playlistShuffle as $audio)
			$audiolist .= "./download.php?id=".$audio['file']."|";
		$audiolist = substr($audiolist, 0, strlen($audiolist)-1);
	
		echo $tab4,'<h2>Lista: ',$row['name'],'</h2>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<p><object type="application/x-shockwave-flash" data="./swf/dewplayer-multi.swf?mp3=',$audiolist,'&amp;autoplay=1" width="240" height="20">',$eol;
		echo $tab6,'<param name="movie" value="./swf/dewplayer-multi.swf?mp3=',$audiolist,'&amp;autoplay=1" />',$eol;
		echo $tab5,'</object></p>',$eol;
		echo $tab5,'<p>Canciones de esta lista, se reproducirán en el mismo orden que se muestran: </p>',$eol;
		echo $tab5,'<ul>',$eol;
		foreach($playlistShuffle as $audio) {
			$sql = $consultasSql->consulta("SELECT fileName FROM ".$miaudio['mysql']['prefix']."files WHERE id = ".$consultasSql->proteger($audio['file']));
			$row = mysql_fetch_array($sql);
			$fileName = str_replace(".".end(explode("[/.]", $row['fileName'])),"",$row['fileName']);
			echo $tab6,'<li>',$fileName,'</li>',$eol;
		}
		echo $tab5,'</ul>',$eol;
		echo $tab4,'</div>',$eol;
	}

	require("./inc/web2.inc.php");

?>