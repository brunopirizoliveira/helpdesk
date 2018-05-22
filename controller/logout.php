<?php

	define('__ROOT__',DIRNAME(DIRNAME(__FILE__)));
	require_once(__ROOT__.'/model/inc.autoload.php');

	SESSION_START();
	
	$logDAO = NEW LogDAO();
	$logDAO->logInsert('Desconectou-se', NULL);
	
	SESSION_DESTROY();
	SETCOOKIE("cookname","", 1);
	SETCOOKIE("cookpass","", 1);
	HEADER("Location: login.php");