<?php
	
	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(DIRNAME(__FILE__)))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');
	
	$chamadoToken = $_REQUEST['token'];
	$opcao = $_REQUEST['opcao'];
	
	$chamadosDAO = new ChamadosDAO();
	$mailer = new Mailer();
	
	switch($opcao){
		//	Utilizado para atribuir o chamado
		case 'pick':	$acompanhamentoTecnico = $_REQUEST['acompanhamentoTecnico'];
						$tecnicoUsername = $_REQUEST['tecnicoUsername'];
						$chamadoPick = $chamadosDAO->chamadoPick($chamadoToken, $acompanhamentoTecnico, $tecnicoUsername);
						
						echo $chamadoPick;
						
						$mailer->mailUser_Pick($chamadoToken);
						break;
		
		//	Utilizado para retirar-se do atendimento ao chamado
		case 'drop':	$chamadoRelease = $chamadosDAO->chamadoRelease($chamadoToken);
		
						echo $chamadoRelease;
						break;
	}