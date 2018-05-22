<?php

	define('__ROOT__',DIRNAME(DIRNAME(__FILE__)));
	require_once(__ROOT__.'/model/inc.autoload.php');

	session_start();

	if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
		$_SESSION['user'] = $_COOKIE['cookname'];
		$_SESSION['pass'] = $_COOKIE['cookpass'];
		header("Location: login_execute.php");
	}

	$tpl = new TemplatePower('../view/_HOME.html');
	$tpl->assignInclude('content','../view/login.html');

	$tpl->prepare();
	
	if($_GET['e'] == 1){
		$error = "<p class='error'>Usuário ou senha incorretos</p>";
		
		$tpl->assign('error',$error);
	}

	$tpl->printToScreen();