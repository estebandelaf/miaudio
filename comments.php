<?php

	/*************************************************/
	/* MODULO PARA LOS COMENTARIOS DE LOS ARCHIVOS   */
	/* Desarrollador: DeLaF www.delaf.tk             */
	/* Mail: esteban.delaf@gmail.com                 */
	/* Ultima version: 21-02-08                      */
	/*************************************************/

	// http://alumnos.elo.utfsm.cl/~delaf/miaudio/comments.php?comentario=hola&fileId=13&fileUserId=3

	require_once("./inc/config.inc.php");

	function showComments ($fileId,$userId) {
			global $miaudio;
			global $consultasSql;
			global $eol;
			global $tab6;
			global $login;
			$sql_com = $consultasSql->consulta("SELECT * FROM ".$miaudio['mysql']['prefix']."comments WHERE fileId = ".$consultasSql->proteger($fileId)." ORDER by date DESC");
			if(mysql_num_rows($sql_com)>0) {
				while($row_com = mysql_fetch_array($sql_com)) {
					$sql_user = $consultasSql->consulta("SELECT username FROM ".$miaudio['mysql']['prefix']."users WHERE id = ".$row_com['userId']);
					$row_user = mysql_fetch_array($sql_user);
					$sql_file = $consultasSql->consulta("SELECT userId FROM ".$miaudio['mysql']['prefix']."files WHERE id = ".$fileId);
					$row_file = mysql_fetch_array($sql_file);
					$usuario = formatearTxt($row_user['username'],"");
					$comentario = formatearTxt($row_com['comment'],"br");
					echo $tab6,'<p><strong>',$usuario,'</strong> el <em>',date("d/m/y H:i",$row_com['date']),'</em> dijo: ',$comentario;
					if($login && ($row_file['userId']==$userId || $row_com['userId']==$userId)) echo ' <a href="./comments.php?eliminar=',$row_com['id'],'&amp;a=',$fileId,'">[eliminar]</a>';
					echo '</p>',$eol;
					echo $tab6,'<hr />',$eol;
				}
			} else {
				echo $tab6,'<p class="center">No hay comentarios publicados.</p>',$eol;
				echo $tab6,'<hr />',$eol;
			}
	}

	if(isset($_POST['comentario']) && isset($_POST['fileId']) && isset($_POST['fileUserId']) && $_POST['comentario']!="") {
			$comentario = $consultasSql->proteger($_POST['comentario']);
			$consultasSql->consulta("INSERT INTO ".$miaudio['mysql']['prefix']."comments (fileId,fileUserId,userId,comment,date) VALUES (".$_POST['fileId'].",".$_POST['fileUserId'].",'".$_SESSION['userId']."','$comentario','".$miaudio['realTime']."')");
			showComments($fileId,$_SESSION['userId']);
	} else {
		if(isset($_GET['eliminar'])) {
			$sql = $consultasSql->consulta("SELECT userId,fileUserId FROM ".$miaudio['mysql']['prefix']."comments WHERE id='".$consultasSql->proteger($_GET['eliminar'])."'");
			$row = mysql_fetch_array($sql);
			// evitar que un usuario pueda editar el grupo de otro
			if($row['userId']!=$_SESSION['userId'] && $row['fileUserId']!=$_SESSION['userId']) {
					echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./error.php?error=4&amp;file=',$_SERVER['PHP_SELF'],'">';
				} else {
				$consultasSql->consulta("DELETE FROM ".$miaudio['mysql']['prefix']."comments WHERE id = ".$consultasSql->proteger($_GET['eliminar']));
				$consultasSql->cerrar();
				echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./play.php?a=',$_GET['a'],'">';
			}
		} else {
			if(isset($_GET['fileId'])) $fileId = $_GET['fileId'];
			else if(!isset($fileId)) $fileId = 0;
			if(isset($_SESSION['userId'])) $sessionId = $_SESSION['userId'];
			else $sessionId = 0;
			showComments($fileId,$sessionId);
		}
	}

?>