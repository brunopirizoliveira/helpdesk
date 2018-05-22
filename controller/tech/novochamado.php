<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');

	$tpl = new TemplatePower(__ROOT__.'/view/tech/_MASTER.html');
	$tpl->assignInclude('content',__ROOT__.'/view/tech/novochamado.html');

	$usr = $_SESSION['info'][0]['name'][0];			// Recebe o nome de quem está logado
	$username = $_SESSION['username'];				// Recebe o ID de quem está logado
	
	$chamadosDAO = new ChamadosDAO();
	$adldap = new adLDAP();
	
	$tpl->prepare();
	
	$tpl->assign('usr',$usr);
	
	$categoria = $chamadosDAO->chamadoCategorias();			// Chama a função que busca as categorias de chamado para listar no select de edição.
	
	// Busca as informações relativas às categorias e as posiciona no select de categorias.
	foreach($categoria as $key => $value){
		$cat = $categoria[$key];
		
		$categoriaID 	= $cat->getCategoriaID();
		$categoriaNome 	= $cat->getCategoriaNome();
		$categoriaArea 	= $cat->getCategoriaArea();
		
		$tpl->newBlock('chamadoCategorias');
		
		$tpl->assign('categoriaId',$categoriaID);
		$tpl->assign('categoriaArea',$categoriaArea);
		$tpl->assign('categoriaNome',$categoriaNome);
		
	}
	
	$funcionarios = $adldap->user()->info("*",array("company","physicaldeliveryofficename","department","mail","name","samaccountname","useraccountcontrol"));
	
	foreach($funcionarios as $key => $value){
		$funcs = $funcionarios[$key];
		
		$funcionarioLogin = $funcs['samaccountname'][0];
		$funcionarioNome = $funcs['name'][0];
		$funcionarioEmpresa = $funcs['company'][0];
		$funcionarioFilial = $funcs['physicaldeliveryofficename'][0];
		$funcionarioSetor = $funcs['department'][0];
		$UAC = $funcs['useraccountcontrol'][0];
		
		
		//
		if($UAC != 66050 && $UAC != 514 && $funcionarioFilial != NULL){
			$tpl->newBlock('listaUsuarios');
			
			$tpl->assign('funcionarioLogin',$funcionarioLogin);
			$tpl->assign('funcNome',STRTOLOWER($funcionarioNome));
			$tpl->assign('funcionarioNome',$funcionarioNome);
			$tpl->assign('funcionarioEmpresa',$funcionarioEmpresa);
			$tpl->assign('funcionarioFilial',$funcionarioFilial);
			$tpl->assign('funcionarioSetor',$funcionarioSetor);
		}
	}

	$tpl->printToScreen();