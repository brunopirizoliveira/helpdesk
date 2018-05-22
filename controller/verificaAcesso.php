<?php

	define('__ROOT__',DIRNAME(DIRNAME(__FILE__)));

	SESSION_START();

	IF(ISSET($_COOKIE['cookuser'])){
	  $_SESSION['username'] = $_COOKIE['cookuser'];
	}

	/*if(!isset($_COOKIE['cookie'])){
		if(isset($_SESSION['login'])){
			session_start();
			session_destroy(); 
			header("Location: login.php");
		}
	}*/


	$tempoAtual = TIME();
			
	IF(EMPTY($_SESSION['username']) || ($tempoAtual - $_SESSION['time']) >= '6000'){
		
		IF(!EMPTY($_SESSION['username'])){
	/*		
			$logDAO = new LogDAO();
			$logDAO->logInsert($_SESSION['username']);
	*/
		}
			
		SESSION_DESTROY(); 
		HEADER("Location: ../../controller/login.php");
		
	}ELSE IF(!EMPTY($_SESSION['username']) && ($tempoAtual - $_SESSION['time']) < '6000'){
		$_SESSION['time'] = $tempoAtual;
	
}