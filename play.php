<?php

	if(!isset($_GET['a']) || $_GET['a']=="")
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./error.php?error=4&amp;file='.$_SERVER['PHP_SELF'].'">';
	else {

		require_once("./inc/web1.inc.php");

		$sql = $consultasSql->consulta("SELECT fileName,userId,date,about,tags,listen,discId,groupId,rate,licenseName,licenseUrl FROM ".$miaudio['mysql']['prefix']."files WHERE id = ".$consultasSql->proteger($_GET['a']));
		$row = mysql_fetch_array($sql);
		// establecer grupo o disco de existir
		if($row['groupId'] || $row['discId']) {
			if($row['groupId'] && !$row['discId']) {
				$sql3 = $consultasSql->consulta("SELECT name,shortName FROM ".$miaudio['mysql']['prefix']."groups WHERE id = ".$row['groupId']);
				$row3 = mysql_fetch_array($sql3);
				$groupDisc = "<a href=\"./g:".formatearTxt($row3['shortName'])."\">".formatearTxt($row3['name'])."</a>";
			} else if($row['discId']) {
				$sql4 = $consultasSql->consulta("SELECT name,groupId FROM ".$miaudio['mysql']['prefix']."discs WHERE id = ".$row['discId']);
				$row4 = mysql_fetch_array($sql4);
				$sql3 = $consultasSql->consulta("SELECT name,shortName FROM ".$miaudio['mysql']['prefix']."groups WHERE id = ".$row4['groupId']);
				$row3 = mysql_fetch_array($sql3);
				$groupDisc = "<a href=\"./g:".formatearTxt($row3['shortName'])."\">".formatearTxt($row3['name'])." / ".formatearTxt($row4['name'])."</a>";
			}
		} else $groupDisc = "";
	
		// actualizar las veces escuchado
		$listen = $row['listen'] + 1;
		$consultasSql->consulta("UPDATE ".$miaudio['mysql']['prefix']."files SET listen = '".$listen."' WHERE id = ".$consultasSql->proteger($_GET['a']));
		// obtener nombre de archivo sin extension
		$fileName = str_replace(".".end(explode("[/.]", $row['fileName'])),"",$row['fileName']);
		// establecer licencia
		if($row['licenseName']!="" && $row['licenseUrl']!="") $license = '<a href="'.$row['licenseUrl'].'">'.$row['licenseName'].'</a>';
		if($row['licenseName']!="" && $row['licenseUrl']=="") $license = $row['licenseName'];
		if($row['licenseName']=="" && $row['licenseUrl']=="") $license = '<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.0/cl/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/2.0/cl/80x15.png" /></a>';
	
		$sql2 = $consultasSql->consulta("SELECT username,avatarFileName,about FROM ".$miaudio['mysql']['prefix']."users WHERE id = ".$row['userId']);
		$row2 = mysql_fetch_array($sql2);
	
		if($row2['avatarFileName']) $avatar = "./avatar.php?uid=".$row['userId'];
		else $avatar = "./images/default_avatar.gif";

		echo $tab4,'<h2>',$fileName,'</h2>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<div class="infoLeft">',$eol;
		echo $tab6,'<p><object type="application/x-shockwave-flash" data="./swf/dewplayer.swf?mp3=./download.php?id=',$_GET['a'],'&amp;autoplay=1" width="200" height="20">',$eol;
		echo $tab7,'<param name="movie" value="./swf/dewplayer.swf?mp3=./download.php?id=',$_GET['a'],'&amp;autoplay=1" />',$eol;
		echo $tab6,'</object></p>',$eol;
		if($row['about']!="") echo $tab6,'<p><strong>Descripción</strong>: ',formatearTxt($row['about']),'</p>',$eol;
		if($groupDisc!="") echo $tab6,'<p><strong>Grupo/disco</strong>: ',$groupDisc,'</p>',$eol;
		if($row['tags']!="") echo $tab6,'<p><strong>Tags</strong>: ',showTags($row['tags']),'</p>',$eol;
		echo $tab6,'<p><strong>Escuchado</strong>: ',$listen,' veces</p>',$eol;
		require_once("./rate.php");
		echo $tab5,'</div>',$eol;
		echo $tab5,'<div class="infoRight" id="infoUser">',$eol;
		echo $tab6,'<img src="',$avatar,'" alt="Avatar de ',$row2['username'],'"  class="right" />',$eol;
		echo $tab6,'Subido por <a href="./',$row2['username'],'">',$row2['username'],' (',$consultasSql->contar($miaudio['mysql']['prefix']."files","userId",$row['userId']),')</a> el ',date("d/m/y",$row['date']),'<br />',$eol;
		if($row2['about']!="") echo $tab6,substr($row2['about'],0,40),'<a href="./',$row2['username'],'">...</a>',$eol;
		echo $tab6,'<p><br />Licencia: ',$license,'</p>',$eol;
		echo $tab5,'</div>',$eol;
		echo $tab5,'<div class="clear"></div>',$eol;
		echo $tab4,'</div>',$eol;

		// otros archivos de audio
		/*echo $tab4,'<h3>Otros archivos de audio relacionados con este</h3>',$eol;
		
		echo $tab4,'<div>',$eol;
		
		echo $tab5,'<div class="boxLeft">',$eol;
		echo $tab6,'<p>Otros archivos del mismo autor.</p>',$eol;
		echo $tab5,'</div>',$eol;

		echo $tab5,'<div class="boxRight">',$eol;
		echo $tab6,'<p>Otros archivos con tags similares.</p>',$eol;
		echo $tab5,'</div>',$eol;
	
		echo $tab5,'<div class="clear"></div>',$eol;
		echo $tab4,'</div>',$eol;*/
		//////////////////////////
	
		echo $tab4,'<h3>Coloca este audio en tu web/blog/foro/fotolog/etc</h3>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<p>Solo debes copiar uno de los siguientes códigos y pegarlo en tu sitio web.</p>',$eol;
		echo $tab5,'<form action="" name="embedForm" id="embedForm">',$eol;
		echo $tab6,'<table>',$eol;
		echo $tab7,'<tr><td>Reproducción con botón play</td><td><input id="embed_code_1" name="embed_code_1" class="embedField" value="&lt;object type=&quot;application/x-shockwave-flash&quot; data=&quot;',$miaudio['url'],'/swf/dewplayer.swf?mp3=',$miaudio['url'],'/download.php?id=',$_GET['a'],'&amp;autoplay=0&quot; width=&quot;200&quot; height=&quot;20&quot;&gt;&lt;param name=&quot;movie&quot; value=&quot;',$miaudio['url'],'/swf/dewplayer.swf?mp3=',$miaudio['url'],'/download.php?id=',$_GET['a'],'&amp;autoplay=0&quot; /&gt;&lt;/object&gt;" onclick="javascript:document.embedForm.embed_code_1.focus();document.embedForm.embed_code_1.select();" readonly="readonly" type="text" /></td></tr>',$eol;
		echo $tab7,'<tr><td>Reproducción automática</td><td><input id="embed_code_2" name="embed_code_2" class="embedField" value="&lt;object type=&quot;application/x-shockwave-flash&quot; data=&quot;',$miaudio['url'],'/swf/dewplayer.swf?mp3=',$miaudio['url'],'/download.php?id=',$_GET['a'],'&amp;autoplay=1&quot; width=&quot;200&quot; height=&quot;20&quot;&gt;&lt;param name=&quot;movie&quot; value=&quot;',$miaudio['url'],'/swf/dewplayer.swf?mp3=',$miaudio['url'],'/download.php?id=',$_GET['a'],'&amp;autoplay=1&quot; /&gt;&lt;/object&gt;" onclick="javascript:document.embedForm.embed_code_2.focus();document.embedForm.embed_code_2.select();" readonly="readonly" type="text" /></td></tr>',$eol;
		echo $tab6,'</table>',$eol;
		echo $tab5,'</form>',$eol;
		echo $tab4,'</div>',$eol;
	
		$fileId = $_GET['a'];
	
		echo $tab4,'<h3>Comentarios</h3>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<div id="comments">',$eol;
		require_once("./comments.php");
		echo $tab5,'</div>',$eol;
		if($login) {
			echo $tab5,'<form action="#" method="post" onsubmit="return sendForm(\'',$miaudio['url'],'/comments.php\',\'comentario=\'+this.comentario.value+\'&fileId=\'+this.fileId.value+\'&fileUserId=\'+this.fileUserId.value,\'comments\',this);">',$eol;
			echo $tab6,'<p class="center">',$eol;
			echo $tab7,'<textarea name="comentario" rows="4" cols="50"></textarea><br />',$eol;
			echo $tab7,'<input type="hidden" name="fileId" value="',$_GET['a'],'" />',$eol;
			echo $tab7,'<input type="hidden" name="fileUserId" value="',$row['userId'],'" />',$eol;
			echo $tab7,'<input type="submit" name="submit" value="Enviar comentario" />',$eol;
			echo $tab6,'</p>',$eol;
			echo $tab5,'</form>',$eol;
			echo $tab5,'<p class="center"><strong>No se permite código HTML.</strong></p>',$eol;
		} else {
			echo $tab5,'<p class="center"><strong>Solo usuarios registrados pueden escribir comentarios.</strong></p>',$eol;
		}
		echo $tab4,'</div>',$eol;

		require_once("./inc/web2.inc.php");

	}

?>