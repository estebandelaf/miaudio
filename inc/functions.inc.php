<?php

	/*************************************************/
	/* FUNCIONES                                     */
	/* Desarrollador: DeLaF www.delaf.tk             */
	/* Mail: esteban.delaf@gmail.com                 */
	/* Ultima version: 18-02-08                      */
	/*************************************************/

	/**
	 * muestra los tag con su link a la busqueda
	 */
	function showTags($tags) {
		$tags = explode(" ",$tags);
		for($i=0;$i<count($tags);$i++) {
			echo '<a href="./search.php?q=',$tags[$i],'">',$tags[$i],'</a> ';
		}
	}

	/**
	 * mostrar cuadro de ayuda
	 */
	function helpBox($div,$txt) {
		echo '<a href="#" onmouseout="ocultar_capa(\'',$div,'\')" onmouseover="mostrar_capa(\'',$div,'\')" >[?]</a><div id="',$div,'" class="help_box">',$txt,'</div>';
	}

	/**
	 * validar tipo mime de un archivo de audio
	 */
	function validarMimeType ($mimeType) {
		global $miaudio;
		for($i=0;$i<count($miaudio['mimeType']);$i++) {
			if($miaudio['mimeType'][$i]==$mimeType)
				return 1;
		}
		return 0;
	}
	
	/**
	 * validar tipo mime de un archivo avatar
	 */
	function validarAvatarMimeType ($mimeType) {
		global $miaudio;
		for($i=0;$i<count($miaudio['avatar']['mimeType']);$i++) {
			if($miaudio['avatar']['mimeType'][$i]==$mimeType)
				return 1;
		}
		return 0;
	}

	/**
	 * muestra el contenido de un arreglo separado por comas
	 */
	function showArray($array) {
		$arrayList = "";
		for($i=0; $i<count($array); $i++) {
			$arrayList .= $array[$i];
			if($i==(count($array)-2)) $arrayList .= " y ";
			else {
				if($i==(count($array)-1)) $arrayList .= ".";
				else $arrayList .= ", ";
			}
		}
		return $arrayList;
	}

	/**
	 * obtiene el nombre de la region a partir del numero de la region
	 */
	function obtenerRegion($region_id) {
		global $miaudio;
		for($i=0;$i<count($miaudio['regChile']);$i++)
			if($miaudio['regChile'][$i][0]==$region_id)
				return $miaudio['regChile'][$i][1];
	}

	/**
	 * validacion mediante una expresion regular del usuario ingresado
	 */
	function validarUsuario($usuario) {
		// revisar que el usuario tenga cierta forma
		return preg_match('/^[a-z\d_-]{4,30}$/i', $usuario);
	}

	/**
	 * validacion mediante una expresion regular del email ingresado
	 * en este momento validad algo@.cl como valido, se debe arreglar eso
	 */
	function validarEmail($email) {
		return preg_match('/^[^0-9][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,4}$/',$email);
	}

	/**
	 * evita que se pueda utilizar codigo html
	 * el codigo html se guarda en la base de datos, pero al mostrarse se bloquea
	 * \param $txt texto que sera formateado
	 * \param $sl tipo del salto de linea
	 * \param $html =1 se permite html, =0 se bloquea html
	 */
	function formatearTxt($txt,$sl = "p",$html = 0) {
		// bloquear html
		if(!$html) {
			$txt = str_replace("<","&lt;",$txt);
			$txt = str_replace(">","&gt;",$txt);
		}
		// saltos de linea
		if($sl=="p") $txt = str_replace("\n","</p><p>",$txt);
		if($sl=="br") $txt = str_replace("\n","<br />",$txt);
		// reemplazos generales
		$txt = str_replace("&","&amp;",$txt);
		return $txt;
	}

	/**
	 * recoge el usuario pasado al archivo
	 * retorna el usuario o bien false si no se ha pasado un usuario
	 */
	function recogerParametrosUrl() {

		// quita el nombre del script que se este ejecutando, dejando la carpeta donde se encuentra el script
		// guarda el valor en $script_folder_name, esto para despues restarselo a la url
		$script_name_array = explode("/",$_SERVER['SCRIPT_NAME']);
		$script_name_elements = count($script_name_array) - 1;
		$script_folder_name = "";
		for( $i = 0; $i < $script_name_elements; $i++)
			$script_folder_name .= $script_name_array[$i]."/";

		// toma la url en la que estamos y la guarda en $uri
		$pos = strpos($_SERVER['REQUEST_URI'], '?');
		if($pos != false)
			$uri = substr($_SERVER['REQUEST_URI'], 0, $pos);
		else
			$uri = $_SERVER['REQUEST_URI'];

		// deja solo las el usuario que pasamos al script sustrayendo los caracteres que ocupa la carpeta donde esta el script
		$suntancia_uri = substr($uri, strlen($script_folder_name));

		// en caso que la url pasada termine con / se le quita a la "variable"
		if(substr($suntancia_uri, strlen($suntancia_uri)-1) == "/")
			$suntancia_uri = substr( $suntancia_uri, 0, strlen($suntancia_uri)-1);

		// se revisa que se pase un usuario por la url, sino se retorna 0
		if($suntancia_uri == "")
			return false;
		else 
			return $suntancia_uri;
	}


	/**
	 * crea una imagen a partir de un string
	 */
	function toImage($string){
		header("Content-type: image/png");
		$font  = 4;
		$width  = ImageFontWidth($font) * strlen($string);
		$height = ImageFontHeight($font);
		$im = imagecreate($width,$height);
		$background_color = imagecolorallocate ($im, 255, 255, 255); //white
		$text_color = imagecolorallocate($im, 0, 0,0); //black text
		imagestring ($im, $font, 0, 0,  $string, $text_color);
		imagepng ($im);
	}

?>