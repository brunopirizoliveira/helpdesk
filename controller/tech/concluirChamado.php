<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');
	
	$mailer 		= new Mailer();
	$chamadoObj 	= new Chamados();
	$chamadosDAO	= new ChamadosDAO();
	
	#	Busca dos dados do técnico para inserção de solução.	#
	
	$chamadoToken		= $_REQUEST['token'];
	$chamadoNome 		= $_SESSION['info'][0]['name'][0];
	$chamadoUsername	= $_SESSION['username'];
	$chamadoAnexo		= $_FILES['inserirAnexo'];
	$chamadoComentario	= $_REQUEST['inserirComentario'];
	
	$chamadoObj->setChamadoToken($chamadoToken);
	$chamadoObj->setSolucaoNome($chamadoNome);
	$chamadoObj->setChamadoUsername($chamadoUsername);
	$chamadoObj->setSolucaoTexto($chamadoComentario);
	
	# 	/ 	#
	
	IF($chamadoComentario){
		$conclusaoSuccess = $chamadosDAO->chamadoDone($chamadoObj);
		
		$mailer->mailUser_Conclusao($chamadoToken);
		
	}
	
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