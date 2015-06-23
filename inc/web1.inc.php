<?php
	ob_start();
	require_once("config.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $miaudio['lang']['default']; ?>" lang="<?php echo $miaudio['lang']['default']; ?>">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="<?php echo $miaudio['site']['desc']; ?>" />
		<meta name="keywords" content="<?php echo $miaudio['site']['keywords'],', ',($parametros)?str_replace(".php","",$parametros):'index'; ?>" />
		<meta name="author" content="<?php echo $miaudio['site']['author']; ?>" />
		<link rel="stylesheet" href="<?php echo $miaudio['url']; ?>/style.css" type="text/css" />
		<link rel="icon" href="<?php echo $miaudio['url']; ?>/favicon.ico" type="image/ico" />
		<link rel="shortcut icon" href="<?php echo $miaudio['url']; ?>/favicon.ico" />
		<script type="text/javascript" language="javascript" src="<?php echo $miaudio['url']; ?>/ajax.js"></script>
		<title><?php echo $miaudio['site']['name']; ?></title>
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<h1><a href="./"><?php echo $miaudio['site']['name']; ?></a></h1>
				<p><?php echo $miaudio['site']['desc']; ?></p>
				<div id="search"><form action="<?php echo $miaudio['url']; ?>/search.php" method="post"><?php echo $miaudio['lang']['search']['search']; ?><input type="text" name="q" class="input"/><input type="image" src="<?php echo $miaudio['url']; ?>/images/icon_search.gif" value="Search" name="search" /></form></div>
			</div>
			<div id="nav">
					<ul>
						<?php
							if(!$login) {
								echo '<li><a href="./login.php">[',$miaudio['lang']['nav']['login'],']</a></li>',$eol;
								echo $tab6,'<li><a href="./signup.php">[',$miaudio['lang']['nav']['signup'],']</a></li>',$eol;
							} else
								echo '<li><a href="./',$_SESSION['username'],'">[',$_SESSION['username'],']</a></li>',$eol;
							echo $tab6,'<li><a href="./upload.php">[',$miaudio['lang']['nav']['upload'],']</a></li>'.$eol;
							echo $tab6,'<li><a href="./playlist.php">[',$miaudio['lang']['nav']['playlist'],']</a></li>',$eol;
							if($login) {
								echo $tab6,'<li><a href="./profile.php">[',$miaudio['lang']['nav']['profile'],']</a></li>',$eol;
								echo $tab6,'<li><a href="./groups.php">[',$miaudio['lang']['nav']['groups'],']</a></li>',$eol;
							}
						?>
						<li><a href="./userlist.php">[<?php echo $miaudio['lang']['nav']['userlist']; ?>]</a></li>
						<li><a href="./about.php">[<?php echo $miaudio['lang']['nav']['about']; ?>]</a></li>
						<?php if($login) echo '<li><a href="./logout.php">[',$miaudio['lang']['nav']['logout'],']</a></li>'; ?>
					</ul>
			</div>
			<div id="body">
