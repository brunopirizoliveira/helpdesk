<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');
	
	$mailer = new Mailer();
	$chamadoObj = new Chamados();
	$chamadosDAO = new ChamadosDAO();
	
	$chamadoUsername 	= $_SESSION['info'][0]['samaccountname'][0];
	$chamadoTipo 		= $_REQUEST['novoTipo'];
	$chamadoArea 		= $_REQUEST['novoArea'];
	$chamadoCategoria 	= $_REQUEST['novoCategoria'];
	$chamadoPrioridade 	= $_REQUEST['novoPrioridade'];
	$chamadoTitulo 		= $_REQUEST['novoTitulo'];
	$chamadoDescricao 	= $_REQUEST['novoDescricao'];
	$chamadoAnexo		= $_FILES['novoAnexo'];
	
	$chamadoEmpresa	= $_SESSION['info'][0]['company'][0];
	$chamadoFilial 	= $_SESSION['info'][0]['physicaldeliveryofficename'][0];
	$chamadoSetor 	= $_SESSION['info'][0]['department'][0];
	$chamadoNome 	= $_SESSION['info'][0]['name'][0];
	
	$chamadoObj->setChamadoUsername($chamadoUsername);
	$chamadoObj->setChamadoTipo($chamadoTipo);
	$chamadoObj->setChamadoArea($chamadoArea);
	$chamadoObj->setChamadoCategoria($chamadoCategoria);
	$chamadoObj->setChamadoPrioridade($chamadoPrioridade);
	$chamadoObj->setChamadoTitulo($chamadoTitulo);
	$chamadoObj->setChamadoDescricao($chamadoDescricao);
	$chamadoObj->setChamadoEmpresa($chamadoEmpresa);
	$chamadoObj->setChamadoFilial($chamadoFilial);
	$chamadoObj->setChamadoSetor($chamadoSetor);
	$chamadoObj->setChamadoNome($chamadoNome);
	
	$chamadoToken = $chamadosDAO->chamadoInsert($chamadoObj);
	
	$mailer->mail_Criacao($chamadoToken);
	
	//	Se existir um anexo, chama a função anexoInsert().
	IF($chamadoAnexo['error'][0] != 4){
		$chamadoAnexoEnv = $chamadosDAO->anexoInsert($chamadoToken, $chamadoAnexo, $chamadoNome);
		
		IF($chamadoAnexoEnv['chk'] < COUNT($chamadoAnexo['name'])){
			$somaErro = COUNT($chamadoAnexo['name'])-$chamadoAnexoEnv['chk'];
			HEADER("Location: acchamado.php?t=".$chamadoToken."&q=".$somaErro."&".HTTP_BUILD_QUERY($chamadoAnexoEnv));
		}ELSE{
			HEADER("Location: acchamado.php?t=".$chamadoToken);
		}
	}ELSE{
		HEADER("Location: acchamado.php?t=".$chamadoToken);
	}