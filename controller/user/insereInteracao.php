<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');
	
	$mailer = new Mailer();
	$chamadoObj = new Chamados();
	$chamadosDAO = new ChamadosDAO();
	
	$chamadoToken		= $_REQUEST['token'];
	$chamadoNome 		= $_SESSION['info'][0]['name'][0];
	$chamadoAnexo		= $_FILES['inserirAnexo'];
	$chamadoComent		= $_REQUEST['inserirComentario'];
	
	$somaInt = 0;
	
	IF($chamadoComent){
		$chamadoComentario = $chamadosDAO->comentarioInsert($chamadoToken, $chamadoComent, $chamadoNome);
		IF($somaInt == 0){
			$mailer->mail_Interacao($chamadoToken, $chamadoComent, $chamadoNome);
			$somaInt++;
		}
	}
	
	//	Se existir um anexo, chama a função anexoInsert().
	IF($chamadoAnexo['error'][0] != 4){
		$chamadoAnexoEnv = $chamadosDAO->anexoInsert($chamadoToken, $chamadoAnexo, $chamadoNome);
		
		IF($chamadoAnexoEnv['chk'] < COUNT($chamadoAnexo['name'])){
			$somaErro = COUNT($chamadoAnexo['name'])-$chamadoAnexoEnv['chk'];
			IF($somaInt == 0 && $chamadoAnexoEnv['chk'] > 0){
				$mailer->mail_Interacao($chamadoToken, $chamadoAnexoEnv['chk']." anexo(s) inserido(s).", $chamadoNome);
				$somaInt++;
			}
			HEADER("Location: acchamado.php?t=".$chamadoToken."&q=".$somaErro."&".HTTP_BUILD_QUERY($chamadoAnexoEnv));
		}ELSE{
			IF($somaInt == 0){
				$mailer->mail_Interacao($chamadoToken, $chamadoAnexoEnv['chk']." anexo(s) inserido(s).", $chamadoNome);
				$somaInt++;
			}
			HEADER("Location: acchamado.php?t=".$chamadoToken);
		}
	}ELSE{
		HEADER("Location: acchamado.php?t=".$chamadoToken);
	}