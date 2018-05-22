<?php
	
	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(DIRNAME(__FILE__)))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');
	
	$chamadoToken = $_REQUEST['token'];
	
	$chamadoTipo 		= $_REQUEST['chamadoTipo'];
	$chamadoArea 		= $_REQUEST['chamadoArea'];
	$chamadoCategoria 	= $_REQUEST['chamadoCategoria'];
	$chamadoPrioridade 	= $_REQUEST['chamadoPrioridade'];
	
	$chamadoObj = new Chamados();
	$chamadosDAO = new ChamadosDAO();
	
	$chamadoObj->setChamadoTipo($chamadoTipo);
	$chamadoObj->setChamadoArea($chamadoArea);
	$chamadoObj->setChamadoCategoria($chamadoCategoria);
	$chamadoObj->setChamadoPrioridade($chamadoPrioridade);
	
	$chamadoUpdate = $chamadosDAO->chamadoUpdate($chamadoToken, $chamadoObj);
	
	echo $chamadoUpdate;