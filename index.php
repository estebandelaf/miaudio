<?php

	require_once("./inc/web1.inc.php");

	echo $tab4,'<h2>Bienvenid@</h2>',$eol;
	
	echo $tab4,'<div>',$eol;
	
	echo $tab5,'<div class="boxLeft">',$eol;
	echo $tab6,'<p>Los siguientes archivos de audio son solo de prueba, se han subido para testear el sistema.</p>',$eol;
	echo $tab6,'<ul>',$eol;
	$sql = $consultasSql->consulta("SELECT id,userId,fileName,size FROM ".$miaudio['mysql']['prefix']."files ORDER by date DESC LIMIT ".$miaudio['audioIndexNew']);
	while ($row = mysql_fetch_array($sql)) {
		$sql2 = $consultasSql->consulta("SELECT username FROM ".$miaudio['mysql']['prefix']."users WHERE id ='".$row['userId']."'");
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
	echo $tab6,'</ul>',$eol;
	echo $tab5,'</div>',$eol;
	
	echo $tab5,'<div class="boxRight">',$eol;
	echo $tab6,'<p class="center">',$eol;
	echo $tab7,'<br />',$eol;
	echo $tab7,'<a href="http://www.sasco.cl"><img src="',$miaudio['url'],'/images/banner_sasco_324x60.png" alt="SASCO" /></a>',$eol;
	echo $tab7,'<br /><br />',$eol;
	echo $tab7,'<script type="text/javascript"><!--',$eol;
	echo $tab8,'google_ad_client = "pub-3829973028217596";',$eol;
	echo $tab8,'google_ad_width = 234;',$eol;
	echo $tab8,'google_ad_height = 60;',$eol;
	echo $tab8,'google_ad_format = "234x60_as";',$eol;
	echo $tab8,'google_ad_type = "text_image";',$eol;
	echo $tab8,'google_ad_channel = "";',$eol;
	echo $tab7,'//-->',$eol;
	echo $tab7,'</script>',$eol;
	echo $tab7,'<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>',$eol;	
	echo $tab6,'</p>',$eol;
	echo $tab5,'</div>',$eol;
	
	echo $tab5,'<div class="clear"></div>',$eol;
	echo $tab4,'</div>',$eol;
	
	echo $tab4,'<h3>Noticias &amp; Change Log</h3>',$eol;
	echo $tab4,'<div class="box">',$eol;
	echo $tab5,'<ul>',$eol;
	echo $tab6,'<li><strong>24/02/08</strong>: funcionan grupos, discos, búsqueda y tags. Faltan las listas de reproducción y archivos relacionados.</li>',$eol;
	echo $tab6,'<li><strong>23/02/08</strong>: añadidas las calificaciones y posibilidad de indicar una licencia a los archivos. Se han completados los textos de los archivos about.php y terms.php</li>',$eol;
	echo $tab6,'<li><strong>22/02/08</strong>: he agregado la posibilidad de dejar comentarios, esta hecho con ajax pero da algunos problemas. Además he detectado que en IE el javascript da errores (habrá que revisarlo).</li>',$eol;
	echo $tab6,'<li><strong>21/02/08</strong>: modificaciones en el perfil de usuario y formulario para subir archivos, además se agregaron los <em>help box</em> como ayuda extra y <em>tags</em> para los archivos.</li>',$eol;
	echo $tab6,'<li><strong>20/02/08</strong>: modificación a clase sql para prevenir sql injection entre otras, también se modificó el perfil de usuario para poder subir un avatar y además se añadieron los links para agregar a ',$miaudio['site']['name'],' en diferentes <em>bookmarks</em>.</li>',$eol;
	echo $tab6,'<li><strong>19/02/08</strong>: registro y sistema multiusuario funcionando.</li>',$eol;
	echo $tab6,'<li><strong>18/02/08</strong>: agregadas verificaciones del tipo y tamaño de archivo en el upload.</li>',$eol;
	echo $tab6,'<li><strong>17/02/08</strong>: inicio este proyecto, hoy queda funcionando el diseño del sitio, subir archivos de audio sin autentificación y poder reproducir estos. Se muestran en portada las últimas canciones. Respecto a la forma de programación varía con la original de MiCD, aquí estoy aprendiendo a usar sesiones, clases y el uso correcto de echo (esto se verá reflejado en el otro proyecto también).</li>',$eol;
	echo $tab5,'</ul>',$eol;
	echo $tab4,'</div>',$eol;
	
	echo $tab4,'<h3>Añadir ',$miaudio['site']['name'],' a marcadores/favoritos online</h3>',$eol;
	echo $tab4,'<div class="box">',$eol;
	echo $tab5,'<div id="bookmarks">',$eol;
	echo $tab6,'<a href="http://del.icio.us/post?url=',$miaudio['url'],'&amp;title=',$miaudio['site']['name'],': ',$miaudio['site']['desc'],'"><img class="sc-tagged" id="delicious" alt="delicious" src="./images/bookmarks.gif"></a>',$eol;
	echo $tab6,'<a href="http://digg.com/design/',$miaudio['site']['name'],': ',$miaudio['site']['desc'],'"><img class="sc-tagged" id="digg" alt="digg" src="./images/bookmarks.gif"></a>',$eol;
	echo $tab6,'<a href="http://technorati.com/cosmos/search.html?url=',$miaudio['url'],'"><img class="sc-tagged" id="technorati" alt="technorati" src="./images/bookmarks.gif"></a>',$eol;
	echo $tab6,'<a href="http://blinklist.com/index.php?Action=Blink/addblink.php&amp;Description=',$miaudio['site']['name'],': ',$miaudio['site']['desc'],'&amp;URL=',$miaudio['url'],'"><img class="sc-tagged" id="blinklist" alt="blinklist" src="./images/bookmarks.gif"></a>',$eol;
	echo $tab6,'<a href="http://furl.net/storeIt.jsp?t=',$miaudio['site']['name'],': ',$miaudio['site']['desc'],'&amp;u=',$miaudio['url'],'"><img class="sc-tagged" id="furl" alt="furl" src="./images/bookmarks.gif"></a>',$eol;
	echo $tab6,'<a href="http://reddit.com/submit?url=',$miaudio['url'],'&amp;title=',$miaudio['site']['name'],': ',$miaudio['site']['desc'],'"><img class="sc-tagged" id="reddit" alt="reddit" src="./images/bookmarks.gif"></a>',$eol;
	echo $tab6,'<a href="http://www.newsvine.com/_tools/seed&amp;save?u=',$miaudio['url'],'&amp;h=',$miaudio['site']['name'],': ',$miaudio['site']['desc'],'"><img class="sc-tagged" id="newsvine" alt="newsvine" src="./images/bookmarks.gif"></a>',$eol;
	echo $tab6,'<a href="http://www.spurl.net/spurl.php?v=3&amp;title=',$miaudio['site']['name'],': ',$miaudio['site']['desc'],'&amp;url=',$miaudio['url'],'"><img class="sc-tagged" id="spurl" alt="spurl" src="./images/bookmarks.gif"></a>',$eol;
	echo $tab6,'<a href="http://synergy2.search.yahoo.com/myresults/bookmarklet?u=',$miaudio['url'],'&amp;t=',$miaudio['site']['name'],': ',$miaudio['site']['desc'],'"><img class="sc-tagged" id="yahoo" alt="yahoo" src="./images/bookmarks.gif"></a>',$eol;
	echo $tab6,'<a href="http://cgi.fark.com/cgi/fark/edit.pl?new_url=',$miaudio['url'],'&amp;new_comment=',$miaudio['site']['name'],': ',$miaudio['site']['desc'],'"><img class="sc-tagged" id="fark" alt="fark" src="./images/bookmarks.gif"></a>',$eol;
	echo $tab5,'</div>',$eol;
	echo $tab4,'</div>',$eol;

	require_once("./inc/web2.inc.php");

?>
