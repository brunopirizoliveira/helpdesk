<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');
	
	$tpl = new TemplatePower(__ROOT__.'/view/tech/_MASTER.html');
	$tpl->assignInclude('content',__ROOT__.'/view/tech/home.html');
	
	$usr = $_SESSION['info'][0]['name'][0];			// Recebe o nome de quem está logado
	$username = $_SESSION['username'];				// Recebe o login de quem está logado
	
	$filtroID = $_POST['filtroId'];
	$filtroStatus = $_POST['filtroStatus'];
	$filtroEmpresa = $_POST['filtroEmpresa'];
	$filtroFilial = $_POST['filtroFilial'];
	$filtroSetor = $_POST['filtroSetor'];
	$filtroTipo = $_POST['filtroTipo'];
	$filtroArea = $_POST['filtroArea'];
	$filtroCategoria = $_POST['filtroCategoria'];
	
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
	IF($filtroEmpresa){
		$qEmpresa = COUNT($filtroEmpresa);
		FOR($i=0;$i<$qEmpresa;$i++){
			IF($i == 0){
				$filtro .= " AND (CH.EMPRESA = ".$filtroEmpresa[$i];
			}ELSE{
				$filtro .= " OR CH.EMPRESA = ".$filtroEmpresa[$i];
			}
		}
		$filtro .= ")";
	}
	IF($filtroFilial){
		$qFilial = COUNT($filtroFilial);
		FOR($i=0;$i<$qFilial;$i++){
			IF($i == 0){
				$filtro .= " AND (CH.FILIAL = ".$filtroFilial[$i];
			}ELSE{
				$filtro .= " OR CH.FILIAL = ".$filtroFilial[$i];
			}
		}
		$filtro .= ")";
	}
	IF($filtroSetor){
		$qSetor = COUNT($filtroSetor);
		FOR($i=0;$i<$qSetor;$i++){
			IF($i == 0){
				$filtro .= " AND (CH.SETORREQUERENTE = ".$filtroSetor[$i];
			}ELSE{
				$filtro .= " OR CH.SETORREQUERENTE = ".$filtroSetor[$i];
			}
		}
		$filtro .= ")";
	}
	IF($filtroTipo){
		$qTipo = COUNT($filtroTipo);
		FOR($i=0;$i<$qTipo;$i++){
			IF($i == 0){
				$filtro .= " AND (CH.TIPO = ".$filtroTipo[$i];
			}ELSE{
				$filtro .= " OR CH.TIPO = ".$filtroTipo[$i];
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
	IF($filtroCategoria){
		$qCategoria = COUNT($filtroCategoria);
		FOR($i=0;$i<$qCategoria;$i++){
			IF($i == 0){
				$filtro .= " AND (CH.CATEGORIA = ".$filtroCategoria[$i];
			}ELSE{
				$filtro .= " OR CH.CATEGORIA = ".$filtroCategoria[$i];
			}
		}
		$filtro .= ")";
	}
	
	$chamadosDAO = new ChamadosDAO();
	$usuarioDAO = new UsuarioDao();
	
	$aviso = $chamadosDAO->avisoBusca();
	$avisoTexto = $aviso->getAvisoTexto();
	if($avisoTexto){	$tpl->assignInclude('aviso',__ROOT__.'/view/tech/aviso.html');	}
	
	$area = $_SESSION['nivel'];							// Verifica qual área do técnico (Infra, Sistemas, Telefonia, Gerência)

	$tpl->prepare();
	
	$tpl->assign('usr',$usr);
	$tpl->assign('tecarea',$area);
	$tpl->assign('avisoMensagem',$avisoTexto);
	
	$tecnicoAcompanhamento = $chamadosDAO->chamadoList($username, 1, $filtro);
	
	IF(COUNT($tecnicoAcompanhamento) == 0){
		$tpl->assign('noResult',"<p class='small pull-right'><strong>Nenhum resultado encontrado.</strong></p>");
	}ELSE{
		foreach($tecnicoAcompanhamento as $key => $value){
			$tecAcmp = $tecnicoAcompanhamento[$key];
			
			$chamadoToken 		= $tecAcmp->getChamadoToken();
			$chamadoID			= $tecAcmp->getChamadoID();
			$chamadoTitulo 		= $tecAcmp->getChamadoTitulo();
			$chamadoAbertura 	= $tecAcmp->getChamadoAbertura();
			$chamadoNome 		= $tecAcmp->getChamadoNome();
			$chamadoPrioridade 	= $tecAcmp->getChamadoPrioridade();
			$chamadoTipo 		= $tecAcmp->getChamadoTipo();
			$chamadoCategoria 	= $tecAcmp->getChamadoCategoria();
			$chamadoStatus 		= $tecAcmp->getChamadoStatus();
			$chamadoStatusID	= $tecAcmp->getChamadoStatusID();
			$chamadoDescricao 	= $tecAcmp->getChamadoDescricao();
			
			$tpl->newBlock('tecnicoAcompanhamento');
			
			if($chamadoStatusID == 3){
				$tpl->assign('solucao','class="success"');
			}
			
			$tpl->assign('chamadoToken',$chamadoToken);
			$tpl->assign('chamadoId',$chamadoID);
			$tpl->assign('chamadoTitulo',$chamadoTitulo);
			$tpl->assign('chamadoAbertura',$chamadoAbertura);
			$tpl->assign('chamadoSolicitante',$chamadoNome);
			$tpl->assign('chamadoPrioridade',$chamadoPrioridade);
			$tpl->assign('chamadoTipo',$chamadoTipo);
			$tpl->assign('chamadoCategoria',$chamadoCategoria);
			$tpl->assign('chamadoStatus',$chamadoStatus);
			$tpl->assign('chamadoDescricao',$chamadoDescricao);
		}
	}

	$tpl->printToScreen();