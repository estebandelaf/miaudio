<?php

	/*************************************************/
	/* CLASE PARA MYSQL                              */
	/* Desarrollador: DeLaF www.delaf.tk             */
	/* Mail: esteban.delaf@gmail.com                 */
	/* Ultima version: 24/02/08                      */
	/*************************************************/

	class mysql {

		// propiedades de la clase
		var $server;
		var $dataBase;
		var $user;
		var $password;
		var $conexionId;
		var $queryId;
		var $errorSql;
	
		// constructor de la clase
		function mysql ($Serv = "", $BD = "", $User = "", $Ps = "") {
			$this->server = $Serv;
			$this->dataBase = $BD;
			$this->user = $User;
			$this->password = $Ps;
		}

		// metodo para conectar a la base de datos
		function conectar ($Serv, $BD, $User, $Ps) {
			if ($Serv != "") $this->server = $Serv;
			if ($BD != "") $this->dataBase = $BD;
			if ($User != "") $this->user = $User;
			if ($Ps != "") $this->password = $Ps;
			// se realiza la conexion Procedemos a conectar con el server
			$this->conexionId = mysql_connect($this->server, $this->user, $this->password);
			if (!$this->conexionId) {
				$this->errorSql = "No se ha podido realizar la conexión";
				$this->error();
			}
			// si no ha habido ningún fallo, seleccionamos la Base de Datos
			if (!@mysql_select_db($this->dataBase, $this->conexionId)) {
				$this->errorSql = "No se ha podido abrir la base de datos " . $this->dataBase;
				$this->error();
			}
			// se retorna el id de la conexion
			return $this->conexionId;
		}

		// metodo para hacer consultas a la base de datos
		function consulta ($sql = "") {
			// se verifica que se ha pasado una sentencia sql 
			if ($sql == "") {
				$this->errorSql = "No se ha indicado la sentencia SQL";
				$this->error();
			}
			// se realiza la consulta
			$this->queryId = mysql_query($sql, $this->conexionId);
			if (!$this->queryId) {
				$this->errorSql = mysql_error();
				$this->error();
			}
			// se retorna el id de la consulta
			return $this->queryId;
		}

		// metodo para indicar error mysql
		function error () {
			echo 'Se ha detectado un problema con la base de datos y la página no puede seguir siendo cargada.<br />';
			echo 'El error fue: ',$this->errorSql;
			$this->cerrar();
			exit();
		}

		// metodo para cerrar la conexion mysql
		function cerrar () {
			if(!mysql_close($this->conexionId)) {
				$this->errorSql = "Ha habido un problema al cerrar la conexión con mysql";
				$this->error();
			}
			return 1;
		}
		
		// metodo para parsear valores pasados a mysql y evitar sql injection
		function proteger($txt = "") {
			if($txt!="") {
				$txt = mysql_real_escape_string($txt);
				if($txt) return $txt;
				else {
					$this->errorSql = "Hubo problemas con los datos que se han utilizado para la consulta a la base de datos";
					$this->error();
				}
			}
			return $txt;
		}
		
		// metodo para contar elementos en una tabla
		function contar ($tabla = "", $columna = "", $condicion = "") {
			if($tabla == "") {
				$this->errorSql = "No se ha indicado una tabla en la que contar elementos";
				$this->error();
			}
			if($columna == "" || $condicion == "") {
				$this->consulta("SELECT SQL_CALC_FOUND_ROWS * FROM ".$tabla);
			} else {
				$this->consulta("SELECT SQL_CALC_FOUND_ROWS * FROM ".$tabla." WHERE ".$columna." = '".$condicion."'");
			}
			$sql = $this->consulta("SELECT FOUND_ROWS()");
			$elementos = mysql_fetch_row($sql);
			return $elementos[0];
		}

	}

?>