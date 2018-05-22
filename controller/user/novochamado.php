<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');

	$tpl = new TemplatePower(__ROOT__.'/view/user/_MASTER.html');
	$tpl->assignInclude('content',__ROOT__.'/view/user/novochamado.html');

	$usr = $_SESSION['info'][0]['name'][0];			// Recebe o nome de quem está logado
	$username = $_SESSION['username'];				// Recebe o ID de quem está logado
	
	$chamadosDAO = new ChamadosDAO();
	
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

	$tpl->printToScreen();