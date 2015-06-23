<?php

	require("./inc/web1.inc.php");
	
	if(!$login) {
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./error.php?error=4&amp;file=',$_SERVER['PHP_SELF'],'">';
	} else {
		
		if(!isset($_GET['editar']) && !isset($_GET['eliminar'])) {
			if(isset($_POST['agregar'])) {
				$sql = $consultasSql->consulta("SELECT userId FROM ".$miaudio['mysql']['prefix']."groups WHERE id='".$consultasSql->proteger($_POST['groupId'])."'");
				$row = mysql_fetch_array($sql);
				// evitar que un usuario pueda agregar un disco a otro usuario
				if($row['userId']!=$_SESSION['userId']) {
					echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./error.php?error=4&amp;file=',$_SERVER['PHP_SELF'],'">';
				} else {
					$consultasSql->consulta("INSERT INTO ".$miaudio['mysql']['prefix']."discs (name,groupId,userId) VALUES ('".$consultasSql->proteger($_POST['name'])."','".$consultasSql->proteger($_POST['groupId'])."','".$_SESSION['userId']."')");
					echo $tab4,'<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./groups.php">',$eol;
				}
			} else {
				echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./error.php?error=4&amp;file=',$_SERVER['PHP_SELF'],'">';
			}
		} else {
			if(isset($_GET['editar'])) {
				$sql = $consultasSql->consulta("SELECT userId FROM ".$miaudio['mysql']['prefix']."discs WHERE id='".$consultasSql->proteger($_GET['discId'])."'");
				$row = mysql_fetch_array($sql);
				// evitar que un usuario pueda editar el disco de otro
				if($row['userId']!=$_SESSION['userId']) {
					echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./error.php?error=4&amp;file=',$_SERVER['PHP_SELF'],'">';
				} else {
					if(isset($_POST['edit'])) {
						$consultasSql->consulta("UPDATE ".$miaudio['mysql']['prefix']."discs SET name = '".$consultasSql->proteger($_POST['name'])."' WHERE id = '".$consultasSql->proteger($_POST['discId'])."'");
						echo $tab4,'<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./groups.php">',$eol;
					} else {
						$sql = $consultasSql->consulta("SELECT * FROM ".$miaudio['mysql']['prefix']."discs WHERE id='".$consultasSql->proteger($_GET['discId'])."'");
						$row = mysql_fetch_array($sql);
						echo $tab4,'<h2>Editar disco: ',$row['name'],'</h2>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab4,'<form action="',$_SERVER['PHP_SELF'],'?editar=1" method="post" onsubmit="return validarDisc(this);">',$eol;
						echo $tab5,'<table>',$eol;
						echo $tab6,'<tr><td>Disco</td><td><input type="text" name="name" size="66" maxlength="250" value="',$row['name'],'" /></td></tr>',$eol;
						echo $tab6,'<tr><td></td><td><input type="hidden" name="groupId" value="',$_GET['groupId'],'"></td></tr>',$eol;
						echo $tab6,'<tr><td></td><td><input type="hidden" name="discId" value="',$_GET['discId'],'"></td></tr>',$eol;
						echo $tab6,'<tr><td></td><td><input type="submit" name="edit" value="Editar"></td></tr>',$eol;
						echo $tab5,'</table>',$eol;
						echo $tab5,'</form>',$eol;
						echo $tab4,'</div>',$eol;
					}					
				}
			}
			if(isset($_GET['eliminar'])) {
				$sql = $consultasSql->consulta("SELECT userId FROM ".$miaudio['mysql']['prefix']."discs WHERE id='".$consultasSql->proteger($_GET['eliminar'])."'");
				$row = mysql_fetch_array($sql);
				// evitar que un usuario pueda eliminar el disco de otro
				if($row['userId']!=$_SESSION['userId']) {
					echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./error.php?error=4&amp;file=',$_SERVER['PHP_SELF'],'">';
				} else {
					$consultasSql->consulta("DELETE FROM ".$miaudio['mysql']['prefix']."discs WHERE id='".$consultasSql->proteger($_GET['eliminar'])."'");
					echo "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=./groups.php'>";
				}
			}
		}

		require("./inc/web2.inc.php");
		
	}

?>