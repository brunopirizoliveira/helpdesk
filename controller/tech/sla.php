<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');
	
	$tpl = new TemplatePower(__ROOT__.'/view/tech/_MASTER.html');
	$tpl->assignInclude('content',__ROOT__.'/view/tech/sla.html');


	$tpl->prepare();

	$chamadosDAO = new ChamadosDAO();

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

