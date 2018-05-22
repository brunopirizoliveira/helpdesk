<?php
	
	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(DIRNAME(__FILE__)))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');
	
	$token = $_REQUEST['token'];
	$action = $_REQUEST['action'];

	$mailer			= new Mailer();
	$chamadosDAO 	= new ChamadosDAO();
	
	switch($action){
		case "apr":	$aprovacao = $chamadosDAO->chamadoFinish($token);
					
					$mailer->mail_Encerramento($token);
					
					echo $aprovacao;
					
					break;
		
		case "rej":	$motivoRejeicao 	= STR_REPLACE("'","", STRIPSLASHES($_REQUEST['motivoRejeicao']));
					$comentarioNome 	= $_REQUEST['comentarioNome'];
					$solucaoID			= $_REQUEST['solucaoID'];
					
					$chamadoObj = new Chamados();
					
					$chamadoObj->setChamadoToken($token);
					$chamadoObj->setComentarioTexto($motivoRejeicao);
					$chamadoObj->setComentarioNome($comentarioNome);
					$chamadoObj->setSolucaoID($solucaoID);
					
					$rejeicao = $chamadosDAO->chamadoDecline($chamadoObj);
					
					echo $rejeicao;
					
					break;
					
		default:	break;
	}