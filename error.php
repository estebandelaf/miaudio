<?php

	/*************************************************/
	/* DESCRIPCION DE ERRORES                        */
	/* Desarrollador: DeLaF www.delaf.tk             */
	/* Mail: esteban.delaf@gmail.com                 */
	/* Ultima version: 18-02-08                      */
	/*************************************************/

	require_once("./inc/web1.inc.php");

	echo $tab4,'<h2>Error #',$_GET['error'],'</h2>',$eol;
	echo $tab4,'<div class="box">',$eol;

	switch($_GET['error']) {
		case 1: {
			echo $tab5,'<p>Debe ingresar el usuario y la contraseña, vuelve atrás a inténtalo denuevo.</p>',$eol;
			break;
		}
		case 2: {
			echo $tab5,'<p>El usuario <em>',$_GET['u'],'</em> es incorrecto.</p>',$eol;
			break;
		}
		case 3: {
			echo $tab5,'<p>Contraseña incorrecta, vuelve atrás y verifícala por favor.</p>',$eol;
			break;
		}
  		case 4: {
			echo $tab5,'<p>Usted no esta autorizado para acceder al archivo <em>',$_GET['file'],'</em> de la forma que lo hizo.</p>',$eol;
			break;
		}
		case 5: {
			echo $tab5,'<p>Las contraseñas ingresadas no son iguales.</p>',$eol;
			break;
		}
		case 6: {
			echo $tab5,'<p>Todos los campos son requeridos, vuelve atrás y verifícalos por favor.</p>',$eol;
			break;
		}
		case 7: {
			echo $tab5,'<p>El grupo <em>',$_GET['g'],'</em> es incorrecto.</p>',$eol;
			break;
		}
		default:
			echo $tab5,'<p>El error #',$_GET['error'],' no encontrado dentro de nuestras descripciones de errores.</p>',$eol;
	}

	echo $tab4,'</div>',$eol;

	require_once("./inc/web2.inc.php");

?>