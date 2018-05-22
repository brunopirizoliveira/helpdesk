<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');

	$tpl = new TemplatePower(__ROOT__.'/view/tech/_MASTER.html');
	$tpl->assignInclude('content',__ROOT__.'/view/tech/funcionarios.html');

	$usr = $_SESSION['info'][0]['name'][0];			// Recebe o nome de quem está logado
	$idusr = $_SESSION['username'];			// Recebe o ID de quem está logado
	
	$adldap = new adLDAP();
	
	$funcionarios = $adldap->user()->info("*",array("company","physicaldeliveryofficename","department","mail","name","samaccountname","useraccountcontrol"));
	
	$tpl->prepare();
	
	$tpl->assign('usr',$usr);
	
	$order = 0;
	
	foreach($funcionarios as $key => $value){
		$workers = $funcionarios[$key];
		
		$funcionarioLogin = $workers['samaccountname'][0];
		$funcionarioNome = $workers['name'][0];
		$funcionarioEmpresa = $workers['company'][0];
		$funcionarioFilial = $workers['physicaldeliveryofficename'][0];
		$funcionarioSetor = $workers['department'][0];
		$UAC = $workers['useraccountcontrol'][0];
		
		
		//
		if($UAC != 66050 && $UAC != 514 && $funcionarioFilial != NULL){
			$order++;
			
			$tpl->newBlock('funcionarioBlock');
			
			$tpl->assign('order',$order);
			$tpl->assign('funcionarioLogin',$funcionarioLogin);
			$tpl->assign('funcionarioNome',$funcionarioNome);
			$tpl->assign('funcionarioEmpresa',$funcionarioEmpresa);
			$tpl->assign('funcionarioFilial',$funcionarioFilial);
			$tpl->assign('funcionarioSetor',$funcionarioSetor);
		}
	}

	$tpl->printToScreen();