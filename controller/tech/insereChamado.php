<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');
	
	$adldap = NEW adLDAP();
	$mailer = NEW Mailer();
	$chamadoObj = NEW Chamados();
	$chamadosDAO = NEW ChamadosDAO();
	
	$chamadoUsername 	= $_REQUEST['novoLogin'];
	$chamadoTipo 		= $_REQUEST['novoTipo'];
	$chamadoArea 		= $_REQUEST['novoArea'];
	$chamadoCategoria 	= $_REQUEST['novoCategoria'];
	$chamadoPrioridade 	= $_REQUEST['novoPrioridade'];
	$chamadoTitulo 		= $_REQUEST['novoTitulo'];
	$chamadoDescricao 	= $_REQUEST['novoDescricao'];
	$chamadoAnexo		= $_FILES['novoAnexo'];
	
	$infosAD = $adldap->user()->info($chamadoUsername,ARRAY("company","physicaldeliveryofficename","department","mail","name","samaccountname"));
	
	$chamadoEmpresa	= $infosAD[0]['company'][0];
	$chamadoFilial 	= $infosAD[0]['physicaldeliveryofficename'][0];
	$chamadoSetor 	= $infosAD[0]['department'][0];
	$chamadoNome 	= $infosAD[0]['name'][0];
	
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