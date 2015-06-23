<?php

	require("./inc/web1.inc.php");
	
	if(!$login || (isset($_GET['g']) && $_GET['g']!="")) {
		if(!isset($_GET['g']) || $_GET['g']=="") {
			echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./error.php?error=4&amp;file=',$_SERVER['PHP_SELF'],'">';
		} else {
			// mostrar informcion del grupo solicitado
			$sql = $consultasSql->consulta("SELECT * FROM ".$miaudio['mysql']['prefix']."groups WHERE shortName = '".$consultasSql->proteger($_GET['g'])."'");
			$row = mysql_fetch_array($sql);
			echo $tab4,'<h2>Grupo ',$row['name'],'</h2>',$eol;
			echo $tab4,'<div class="box">',$eol;
			echo $tab5,'<p>',$row['about'],'</p>',$eol;
			echo $tab4,'</div>',$eol;
			
			echo $tab4,'<h3>Archivos de audio</h3>',$eol;
			echo $tab4,'<div class="box">',$eol;
			if($consultasSql->contar($miaudio['mysql']['prefix']."files","groupId",$row['id'])) {
				echo $tab5,'<p>Los siguientes archivos de audio pertenecen al grupo ',$row['name'],'</p>',$eol;
				echo $tab5,'<ul>',$eol;
				$sql2 = $consultasSql->consulta("SELECT id,filename,size FROM ".$miaudio['mysql']['prefix']."files WHERE groupId = '".$row['id']."' ORDER by date DESC");
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
				echo $tab5,'<p>No existen archivos de audio subidos para este grupo.</p>',$eol;
			}
			echo $tab4,'</div>',$eol;
			
		}
	} else {
		
		if(!isset($_GET['editar']) && !isset($_GET['eliminar'])) {
			if(isset($_POST['agregar'])) {
				if(!$consultasSql->contar($miaudio['mysql']['prefix']."groups","shortName",$consultasSql->proteger($_POST['shortName']))) {
					$consultasSql->consulta("INSERT INTO ".$miaudio['mysql']['prefix']."groups (userId,shortName,name,about) VALUES (".$_SESSION['userId'].",'".$consultasSql->proteger($_POST['shortName'])."','".$consultasSql->proteger($_POST['name'])."','".$consultasSql->proteger($_POST['about'])."')");
					echo $tab4,'<META HTTP-EQUIV="Refresh" CONTENT="0; URL=',$_SERVER['PHP_SELF'],'">',$eol;
				} else {
					echo $tab4,'<h2>Error: nombre corto ya existe</h2>',$eol;
					echo $tab4,'<div class="box">',$eol;
					echo $tab5,'<p>El nombre corto del grupo debe ser único, el que has seleccionado ya se encuentra en uso. Por favor vuelve atrás he indica otro.</p>',$eol;
					echo $tab4,'</div>',$eol;		
				}
			} else {

				echo $tab4,'<h2>Grupos</h2>',$eol;
				echo $tab4,'<div class="box">',$eol;
				echo $tab5,'<p>Los grupos son una forma de clasificar los archivos de audio que tu subas. Además cada grupo podrá tener una subcategoría o disco (para bandas musicales) para poder ordenar de la mejor forma los archivos de audio. Para poder crear un disco primero debes definir un grupo.</p>',$eol;
				echo $tab4,'</div>',$eol;

				echo $tab4,'<h3>Agregar grupo</h3>',$eol;
				echo $tab4,'<div class="box">',$eol;
				echo $tab5,'<form action="',$_SERVER['PHP_SELF'],'" method="post" onsubmit="return validarGroup(this);">',$eol;
				echo $tab5,'<table>',$eol;
				echo $tab6,'<tr><td>Grupo</td><td><input type="text" name="name" size="66" maxlength="250" /></td></tr>',$eol;
				echo $tab6,'<tr><td>Nombre corto</td><td><input type="text" name="shortName" size="66" maxlength="30" /></td></tr>',$eol;
				echo $tab6,'<tr><td>Descripción</td><td><textarea name="about" rows="5" cols="50"></textarea></td></tr>',$eol;
				echo $tab6,'<tr><td></td><td><input type="submit" name="agregar" value="Agregar"></td></tr>',$eol;
				echo $tab5,'</table>',$eol;
				echo $tab5,'</form>',$eol;
				echo $tab4,'</div>',$eol;
		
		
				echo $tab4,'<h3>Editar/eliminar grupos &amp; crear discos</h3>',$eol;
				echo $tab4,'<div class="box">',$eol;
				echo $tab5,'<p>Para poder eliminar un grupo no pueden haber archivos asociados a él.</p>',$eol;
				$sql = $consultasSql->consulta("SELECT id,name FROM ".$miaudio['mysql']['prefix']."groups WHERE userId = ".$_SESSION['userId']." ORDER BY name ASC");
				echo $tab5,'<table>',$eol;
				echo $tab6,'<tr><th>Grupo<td></td><td></td></tr>',$eol;
				while ($row = mysql_fetch_array($sql)) {
					echo $tab6,'<tr>',$eol;
					echo $tab7,'<td>',$row['name'],'</td>',$eol;
					echo $tab7,'<td><a href="',$_SERVER['PHP_SELF'],'?editar=1&amp;groupId=',$row['id'],'">[editar]</a></td>',$eol;
					echo $tab7,'<td>',$eol;
					$ndiscos = $consultasSql->contar($miaudio['mysql']['prefix']."files","groupId",$row['id']);
					if(!$ndiscos) echo '<a href="#" onclick="eliminar(\'',$row['id'],'\',\'',$row['name'],'\',\'',$_SERVER['PHP_SELF'],'\');">[eliminar]</a>';
					else echo"$ndiscos archivos pertenecen a este grupo";
					echo $tab7,'</td>',$eol;
					echo $tab6,'</tr>',$eol;
				}
				echo $tab5,'</table>',$eol;
				echo $tab4,'</div>',$eol;
			}
		} else {
			if(isset($_GET['editar'])) {
				$sql = $consultasSql->consulta("SELECT userId FROM ".$miaudio['mysql']['prefix']."groups WHERE id='".$consultasSql->proteger($_GET['groupId'])."'");
				$row = mysql_fetch_array($sql);
				// evitar que un usuario pueda editar el grupo de otro
				if($row['userId']!=$_SESSION['userId']) {
					echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./error.php?error=4&amp;file=',$_SERVER['PHP_SELF'],'">';
				} else {
					if(isset($_POST['edit'])) {
						$consultasSql->consulta("UPDATE ".$miaudio['mysql']['prefix']."groups SET name = '".$consultasSql->proteger($_POST['name'])."', about = '".$consultasSql->proteger($_POST['about'])."' WHERE id = '".$consultasSql->proteger($_POST['groupId'])."'");
						echo $tab4,'<META HTTP-EQUIV="Refresh" CONTENT="0; URL=',$_SERVER['PHP_SELF'],'">',$eol;
					} else {
						$sql = $consultasSql->consulta("SELECT * FROM ".$miaudio['mysql']['prefix']."groups WHERE id='".$consultasSql->proteger($_GET['groupId'])."'");
						$row = mysql_fetch_array($sql);
						echo $tab4,'<h2>Editar grupo: ',$row['name'],'</h2>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab4,'<form action="',$_SERVER['PHP_SELF'],'?editar=1" method="post" onsubmit="return validarGroup(this);">',$eol;
						echo $tab5,'<table>',$eol;
						echo $tab6,'<tr><td>Grupo</td><td><input type="text" name="name" size="66" maxlength="250" value="',$row['name'],'" /></td></tr>',$eol;
						echo $tab6,'<tr><td>Nombre corto</td><td><input type="text" name="shortName" size="66" maxlength="30" value="',$row['shortName'],'" readonly /></td></tr>',$eol;
						echo $tab6,'<tr><td>Descripción</td><td><textarea name="about" rows="5" cols="50">',$row['about'],'</textarea></td></tr>',$eol;
						echo $tab6,'<tr><td></td><td><input type="hidden" name="groupId" value="',$_GET['groupId'],'"></td></tr>',$eol;
						echo $tab6,'<tr><td></td><td><input type="submit" name="edit" value="Editar"></td></tr>',$eol;
						echo $tab5,'</table>',$eol;
						echo $tab5,'</form>',$eol;
						echo $tab4,'</div>',$eol;
					
						echo $tab4,'<h3>Agregar disco</h3>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab5,'<form action="./discs.php" method="post" onsubmit="return validarDisc(this);">',$eol;
						echo $tab5,'<table>',$eol;
						echo $tab6,'<tr><td>Disco</td><td><input type="text" name="name" size="66" maxlength="250" /></td></tr>',$eol;
						echo $tab6,'<tr><td></td><td><input type="hidden" name="groupId" value="',$_GET['groupId'],'"></td></tr>',$eol;
						echo $tab6,'<tr><td></td><td><input type="submit" name="agregar" value="Agregar"></td></tr>',$eol;
						echo $tab5,'</table>',$eol;
						echo $tab5,'</form>',$eol;
						echo $tab4,'</div>',$eol;
					
						echo $tab4,'<h3>Editar/eliminar discos</h3>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab5,'<p>Para poder eliminar un disco no pueden haber archivos asociados a él.</p>',$eol;
						$sql = $consultasSql->consulta("SELECT id,name FROM ".$miaudio['mysql']['prefix']."discs WHERE groupId = ".$consultasSql->proteger($_GET['groupId'])." ORDER BY name ASC");
						echo $tab5,'<table>',$eol;
						echo $tab6,'<tr><th>Disco<td></td><td></td></tr>',$eol;
						while ($row = mysql_fetch_array($sql)) {
							echo $tab6,'<tr>',$eol;
							echo $tab7,'<td>',$row['name'],'</td>',$eol;
							echo $tab7,'<td><a href="./discs.php?editar=1&amp;discId=',$row['id'],'&amp;groupId=',$_GET['groupId'],'">[editar]</a></td>',$eol;
							echo $tab7,'<td>';
							$ndiscos = $consultasSql->contar($miaudio['mysql']['prefix']."files","discId",$row['id']);
							if(!$ndiscos) echo '<a href="#" onclick="eliminar(\'',$row['id'],'\',\'',$row['name'],'\',\'./discs.php\');">[eliminar]</a>';
							else echo"$ndiscos archivos pertenecen a este disco";
							echo $tab7,'</td>',$eol;
							echo $tab6,'</tr>',$eol;
						}
						echo $tab5,'</table>',$eol;
						echo $tab4,'</div>',$eol;
					}
				}
			}
			if(isset($_GET['eliminar'])) {
				$sql = $consultasSql->consulta("SELECT userId FROM ".$miaudio['mysql']['prefix']."groups WHERE id='".$consultasSql->proteger($_GET['eliminar'])."'");
				$row = mysql_fetch_array($sql);
				// evitar que un usuario pueda editar eliminar un grupo de otro
				if($row['userId']!=$_SESSION['userId']) {
					echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./error.php?error=4&amp;file=',$_SERVER['PHP_SELF'],'">';
				} else {
					$consultasSql->consulta("DELETE FROM ".$miaudio['mysql']['prefix']."groups WHERE id='".$consultasSql->proteger($_GET['eliminar'])."'");
					echo "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=".$_SERVER['PHP_SELF']."'>";
				}
			}
		}
		
	}
	
	require("./inc/web2.inc.php");

?>