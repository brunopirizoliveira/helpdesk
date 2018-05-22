<?php
	
	define('__ROOT__',DIRNAME(DIRNAME(__FILE__)));
	require_once(__ROOT__.'/model/inc.autoload.php');
	
	//Força o navegador a utilizar SSL
	/*if ($_SERVER["SERVER_PORT"] != 443){ 
		header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']); 
		exit(); 
	}*/
	
	$username = STR_REPLACE("'","", STRIPSLASHES(STRTOUPPER($_POST["user"]))); //Remove case-sensitivity
	$password = STR_REPLACE("'","", STRIPSLASHES($_POST["pass"]));

	if($username != NULL && $password != NULL){
		try{
			$adldap = new adLDAP(); //chama a classe adLDAP
		}
		catch (adLDAPException $e){
			echo $e;
			exit();
		}
		
		if($adldap->authenticate($username,$password)){ //Autentica o usuário no AD
			session_start();
			$_SESSION["username"] = $username;
			$_SESSION["info"] = $adldap->user()->info($username,array("company","physicaldeliveryofficename","department","mail","name","samaccountname"));
			$_SESSION["time"] = time();
			
			$logDAO = new LogDAO();
			$logDAO->logInsert('Conectou-se', NULL);
			
			$usuarioDAO = new UsuarioDAO(); //Instancia classe 
			$acesso = $usuarioDAO->nivelAcesso($username); //Envia $username ao método para retornar o nível de acesso
			
			$_SESSION['nivel'] = $acesso;
			
			if($_SESSION['nivel'] != '0'){
				header('Location: tech/home.php');
			}else if($_SESSION['nivel'] == '0'){
				header('Location: user/home.php');
			}
			exit;
			
		}else{
			header('Location: login.php?e=1');
		}
	}else{
		header('Location: login.php');
	}