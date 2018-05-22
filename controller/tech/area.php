<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');

	$tpl = new TemplatePower(__ROOT__.'/view/tech/_MASTER.html');
	$tpl->assignInclude('content',__ROOT__.'/view/tech/area.html');
	
	$usr = $_SESSION['info'][0]['name'][0];			// Recebe o nome de quem está logado
	$username = $_SESSION['username'];				// Recebe o login de quem está logado
	
	$filtroID = $_POST['filtroId'];
	$filtroStatus = $_POST['filtroStatus'];
	$filtroEmpresa = $_POST['filtroEmpresa'];
	$filtroFilial = $_POST['filtroFilial'];
	$filtroSetor = $_POST['filtroSetor'];
	$filtroTipo = $_POST['filtroTipo'];
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
	
	$areaAcompanhamento = $chamadosDAO->chamadoWaiting($filtro);
	
	foreach($areaAcompanhamento as $key => $value){
		$areaAcmp = $areaAcompanhamento[$key];
		
		$chamadoToken 		= $areaAcmp->getChamadoToken();
		$chamadoID			= $areaAcmp->getChamadoID();
		$chamadoTitulo 		= $areaAcmp->getChamadoTitulo();
		$chamadoAbertura 	= $areaAcmp->getChamadoAbertura();
		$chamadoAtualizacao = $areaAcmp->getAcompanhamentoData();
		$chamadoNome 		= $areaAcmp->getChamadoNome();
		$chamadoPrioridade 	= $areaAcmp->getChamadoPrioridade();
		$chamadoTipo 		= $areaAcmp->getChamadoTipo();
		$chamadoCategoria 	= $areaAcmp->getChamadoCategoria();
		$chamadoStatus 		= $areaAcmp->getChamadoStatus();
		$chamadoStatusID	= $areaAcmp->getChamadoStatusID();
		$chamadoDescricao 	= $areaAcmp->getChamadoDescricao();
		$chamadoTecnico 	= $areaAcmp->getAcompanhamentoTecnico();
		$chamadoArea		= $areaAcmp->getChamadoArea();
		
		if($chamadoArea == 1){	$tpl->newBlock('infraAcompanhamento');		}
		if($chamadoArea == 2){	$tpl->newBlock('sistemasAcompanhamento');	}
		if($chamadoArea == 3){	$tpl->newBlock('telefoniaAcompanhamento');	}
		if($chamadoStatusID == 3){
			$tpl->assign('solucao','class="success"');
		}
		
		
		$tpl->assign('chamadoToken',$chamadoToken);
		$tpl->assign('chamadoId',$chamadoID);
		$tpl->assign('chamadoTitulo',$chamadoTitulo);
		$tpl->assign('chamadoAbertura',$chamadoAbertura);
		$tpl->assign('chamadoAtualizacao',$chamadoAtualizacao);
		$tpl->assign('chamadoSolicitante',$chamadoNome);
		$tpl->assign('chamadoPrioridade',$chamadoPrioridade);
		$tpl->assign('chamadoTipo',$chamadoTipo);
		$tpl->assign('chamadoStatus',$chamadoStatus);
		$tpl->assign('chamadoDescricao',$chamadoDescricao);
		$tpl->assign('chamadoCategoria',$chamadoCategoria);
		$tpl->assign('chamadoTecnico',$chamadoTecnico);
	}
	
	

	$tpl->printToScreen();