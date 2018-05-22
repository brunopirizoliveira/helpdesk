<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');

	$tpl = new TemplatePower(__ROOT__.'/view/user/_MASTER.html');
	$tpl->assignInclude('content',__ROOT__.'/view/user/home.html');
	
	$usr = $_SESSION['info'][0]['name'][0];			// Recebe o nome de quem está logado
	$username = $_SESSION['username'];				// Recebe o login de quem está logado
	
	$filtroID = $_POST['filtroId'];
	$filtroStatus = $_POST['filtroStatus'];
	$filtroArea = $_POST['filtroArea'];
	
	$filtro = "";
	
	IF($filtroID){
		SETTYPE($filtroID,'int');
		$filtro .= " AND CH.IDCHAMADO = ".$filtroID;
	}
	IF($filtroStatus){
		$qStatus = COUNT($filtroStatus);
		FOR($i=0;$i<$qStatus;$i++){
			IF($i == 0){
				$filtro .= " AND (CH.STATUS = ".$filtroStatus[$i];
			}ELSE{
				$filtro .= " OR CH.STATUS = ".$filtroStatus[$i];
			}
		}
		$filtro .= ")";
	}
	IF($filtroArea){
		$qArea = COUNT($filtroArea);
		FOR($i=0;$i<$qArea;$i++){
			IF($i == 0){
				$filtro .= " AND (CH.AREA = ".$filtroArea[$i];
			}ELSE{
				$filtro .= " OR CH.AREA = ".$filtroArea[$i];
			}
		}
		$filtro .= ")";
	}
		
	$ChamadosDAO = new ChamadosDAO();
	
	$aviso = $ChamadosDAO->avisoBusca();
	$avisoTexto = $aviso->getAvisoTexto();
	if($avisoTexto){	$tpl->assignInclude('aviso',__ROOT__.'/view/user/aviso.html');	}
	
	$tpl->prepare();
	
	$tpl->assign('usr',$usr);
	$tpl->assign('avisoMensagem',$avisoTexto);

	$usuarioAcompanhamento = $ChamadosDAO->chamadoList($username,0,$filtro);

	IF(COUNT($usuarioAcompanhamento) == 0){
		$tpl->assign('noResult',"<p class='small pull-right'><strong>Nenhum resultado encontrado.</strong></p>");
	}ELSE{
		foreach($usuarioAcompanhamento as $key => $value){
			$uAcmp = $usuarioAcompanhamento[$key];
			
			$chamadoToken		= $uAcmp->getChamadoToken();
			$chamadoID			= $uAcmp->getChamadoID();
			$chamadoTitulo 		= $uAcmp->getChamadoTitulo();
			$chamadoAbertura 	= $uAcmp->getChamadoAbertura();
			$chamadoAtualizacao = $uAcmp->getAcompanhamentoData();
			$chamadoTecnico		= $uAcmp->getAcompanhamentoTecnico();
			$chamadoPrioridade 	= $uAcmp->getChamadoPrioridade();
			$chamadoTipo 		= $uAcmp->getChamadoTipo();
			$chamadoCategoria 	= $uAcmp->getChamadoCategoria();
			$chamadoStatus 		= $uAcmp->getChamadoStatus();
			$chamadoStatusID	= $uAcmp->getChamadoStatusID();
			$chamadoDescricao 	= $uAcmp->getChamadoDescricao();
			
			$tpl->newBlock('usuarioAcompanhamento');
			if($chamadoStatusID == 3){
				$tpl->assign('solucao','class="success"');
			}
			
			$tpl->assign('chamadoToken',$chamadoToken);
			$tpl->assign('chamadoId',$chamadoID);
			$tpl->assign('chamadoTitulo',$chamadoTitulo);
			$tpl->assign('chamadoAbertura',$chamadoAbertura);
			$tpl->assign('chamadoAtualizacao',$chamadoAtualizacao);
			$tpl->assign('chamadoTecnico',$chamadoTecnico);
			$tpl->assign('chamadoPrioridade',$chamadoPrioridade);
			$tpl->assign('chamadoTipo',$chamadoTipo);
			$tpl->assign('chamadoCategoria',$chamadoCategoria);
			$tpl->assign('chamadoStatus',$chamadoStatus);
			$tpl->assign('chamadoDescricao',$chamadoDescricao);
		}
	}
	

	$tpl->printToScreen();