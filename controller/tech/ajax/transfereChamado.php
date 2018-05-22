<?php
	
	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(DIRNAME(__FILE__)))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');
	
	$chamadoToken = $_REQUEST['token'];
	
	$areaDesc			= $_REQUEST['areaDesc'];
	$chamadoArea 		= $_REQUEST['chamadoArea'];
	$categoriaDesc		= $_REQUEST['categoriaDesc'];
	$chamadoCategoria 	= $_REQUEST['chamadoCategoria'];
	
	$chamadoObj = new Chamados();
	$chamadosDAO = new ChamadosDAO();
	
	$chamadoObj->setChamadoAreaID($chamadoArea);
	$chamadoObj->setChamadoCategoriaID($chamadoCategoria);
	$chamadoObj->setChamadoArea($areaDesc);
	$chamadoObj->setChamadoCategoria($categoriaDesc);
	
	$chamadoTransfere = $chamadosDAO->chamadoTransfere($chamadoToken, $chamadoObj);
	
	echo $chamadoTransfere;