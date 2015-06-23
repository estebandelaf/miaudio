<?php

	require("./inc/web1.inc.php");
	
	if(isset($_POST['q'])) {
		$q = $_POST['q'];
		$donde = "filename";
	} else
		if(isset($_GET['q'])) {
			$q = $_GET['q'];
			$donde = "tags";
		} else
			$q = "";
	
	if(isset($q) && strlen($q)>3) {
	
		echo $tab4,'<h2>Búsqueda de palabra ',$q,'</h2>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<ul>',$eol;
		
		$sql = $consultasSql->consulta("SELECT id,userId,fileName,size FROM ".$miaudio['mysql']['prefix']."files WHERE MATCH(".$donde.") AGAINST ('".$consultasSql->proteger($q)."')");
		while($row = mysql_fetch_array($sql)) {
			$sql2 = $consultasSql->consulta("SELECT username FROM ".$miaudio['mysql']['prefix']."users WHERE id = ".$row['userId']);
			$row2 = mysql_fetch_array($sql2);
			$fileName = str_replace(".".end(explode("[/.]", $row['fileName'])),"",$row['fileName']);
			echo $tab7,'<li>',$eol;
			echo $tab8,$fileName,' (',round($row['size']/1024,1),' KB)<br />',$eol;
			echo $tab8,'Archivo subido por <a href="./',$row2['username'],'">',$row2['username'],'</a><br />',$eol;
			echo $tab8,'<a href="./play.php?a='.$row['id'].'">[escuchar]</a>',$eol;
			echo $tab8,'<a href="./download.php?id=',$row['id'],'">[bajar]</a>',$eol;
			echo $tab8,'<a href="#" onclick="agregarLista(',$row['id'],')">[agregar a la lista]</a>',$eol;
			echo $tab7,'</li>',$eol;
		}
		echo $tab5,'</ul>',$eol;
		echo $tab4,'</div>',$eol;
		
	} else {
		echo $tab4,'<h2>Aviso</h2>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<p>Para buscar debes ingresar una palabra de más de 4 caracteres.</p>',$eol;
		echo $tab4,'</div>',$eol;
	}
	
	echo $tab4,'<h3>Tags más usados</h3>',$eol;
	echo $tab4,'<div class="box">',$eol;
	echo $tab5,'<p>Puedes realizar la búsqueda tambien por nuestros tags. ',helpBox("help_tag","Mientras más grande el tamaño de letra del tag significa que más archivos hay relacionados."),'</p>',$eol;
	// seleccionar tag mas usados
	$sql = $consultasSql->consulta("SELECT tag,files FROM ".$miaudio['mysql']['prefix']."tags ORDER by files DESC LIMIT 0,".$miaudio['showTags']);
	while($row = mysql_fetch_array($sql)) {
		// guardarlos en una matriz
		$tags[] = array('tag' => $row['tag'], 'files' => $row['files']);
	}
	// obtener una lista de columnas
	foreach ($tags as $llave => $fila) {
		$tag[$llave]  = $fila['tag'];
	}
	// ordenar la matriz por orden alfabetico de tag
	array_multisort($tag, SORT_ASC, $tags);
	// mostrar tags
	foreach($tags as $tag) {
		// calcular el fontsize
		$fontsize = $tag['files']."em";
		echo $tab5,'<a href="./search.php?q=',$tag['tag'],'" style="font-size: ',$fontsize,';" title="Existen ',$tag['files'],' archivos asociados a este tag">',$tag['tag'],'</a>',$eol;
	}
	echo $tab4,'</div>',$eol;

	require("./inc/web2.inc.php");

?>
