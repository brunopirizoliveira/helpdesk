<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/controller/verificaAcesso.php');

	$tpl = new TemplatePower(__ROOT__.'/view/tech/_MASTER.html');
	$tpl->assignInclude('content',__ROOT__.'/view/tech/acchamado.html');

	$chamadosDAO = new ChamadosDAO();
	
	$usuarioNome 	= $_SESSION['info'][0]['name'][0];	// Nome completo
	$usuarioLogin 	= $_SESSION['username'];			// Chave de acesso à rede (login)
	$usuarioAcesso	= $_SESSION['nivel'];				// Nivel de acesso (user/tecnico[area])
	
	if($_REQUEST['t'] == NULL){		header('Location: '.__ROOT__.'/controller/tech/home.php');	}
	else{	$chamadoToken	= $_REQUEST['t'];	}
	
	if($_REQUEST['f']){
		$fList = $_REQUEST['f'];
		$erros = $_REQUEST['q'];
		$success = $_REQUEST['chk'];
		$total = $erros+$success;
		
		$uploadErr = "";
		$uploadErr .= "	<div id='uploadError' class='alert alert-danger alert-dismissible' role='alert'>
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
								<div class='col-xs-6'>
									Falha de envio em <strong>".$erros."</strong> de <strong>".$total."</strong> arquivos!
									<ul>";
		FOREACH($fList as $key => $value){
			$file = $fList[$key]['f'];
			$err = $fList[$key]['e'];
			
			switch($err){
				case '1':	$uploadErr .= "<li>".$file." (extensão não permitida)</li>";
							break;
				case '2':	$uploadErr .= "<li>".$file." (tamanho acima de 10MB)</li>";
							break;
				default:	break;
				
			}
			
		}
		$uploadErr .= "			</ul>
							</div>
							<div style='clear:both; float:none'></div>
						</div>";
	}
	
	$chamado = $chamadosDAO->chamadoInfo($chamadoToken);	// Chama a função que busca todas informações relativas ao chamado aberto.
	
	$chamadoTitulo	 		= $chamado->getChamadoTitulo();
	$chamadoID 				= $chamado->getChamadoID();
	$chamadoStatusID		= $chamado->getChamadoStatusID();
	$chamadoStatus 			= $chamado->getChamadoStatus();
	$chamadoTecnico 		= $chamado->getAcompanhamentoTecnico();
	$chamadoSolicitante 	= $chamado->getChamadoNome();
	$chamadoEmpresa			= $chamado->getChamadoEmpresa();
	$chamadoFilial			= $chamado->getChamadoFilial();
	$chamadoSetor			= $chamado->getChamadoSetor();
	$chamadoAbertura 		= $chamado->getChamadoAbertura();
	$chamadoAtualizacao 	= $chamado->getAcompanhamentoData();
	$chamadoTipo 			= $chamado->getChamadoTipo();
	$chamadoTipoID 			= $chamado->getChamadoTipoID();
	$chamadoArea 			= $chamado->getChamadoArea();
	$chamadoAreaID 			= $chamado->getChamadoAreaID();
	$chamadoCategoria 		= $chamado->getChamadoCategoria();
	$chamadoCategoriaID 	= $chamado->getChamadoCategoriaID();
	$chamadoPrioridade 		= $chamado->getChamadoPrioridade();
	$chamadoPrioridadeID 	= $chamado->getChamadoPrioridadeID();
	$chamadoDescricao 		= $chamado->getChamadoDescricao();
	
	// Inicia o TemplatePower
	$tpl->prepare();
	
	$tpl->assign('usr',$usuarioNome);
	$tpl->assign('usernameTecnico',$usuarioLogin);
	$tpl->assign('token',$chamadoToken);
	
	if($uploadErr){	$tpl->assign('uploadError',$uploadErr);	}
	
	$tpl->assign('chamadoTitulo',$chamadoTitulo);
	$tpl->assign('chamadoId',$chamadoID);
	$tpl->assign('chamadoStatusId',$chamadoStatusID);
	$tpl->assign('chamadoStatus',$chamadoStatus);
	$tpl->assign('chamadoTecnico',$chamadoTecnico);
	$tpl->assign('chamadoSolicitante',$chamadoSolicitante);
	$tpl->assign('chamadoEmpresa',$chamadoEmpresa);
	$tpl->assign('chamadoFilial',$chamadoFilial);
	$tpl->assign('chamadoSetor',$chamadoSetor);
	$tpl->assign('chamadoAbertura',$chamadoAbertura);
	$tpl->assign('chamadoAtualizacao',$chamadoAtualizacao);
	$tpl->assign('chamadoTipo',$chamadoTipo);
	$tpl->assign('chamadoTipoId',$chamadoTipoID);
	$tpl->assign('chamadoArea',$chamadoArea);
	$tpl->assign('chamadoAreaId',$chamadoAreaID);
	$tpl->assign('chamadoCategoria',$chamadoCategoria);
	$tpl->assign('chamadoCategoriaId',$chamadoCategoriaID);
	$tpl->assign('chamadoPrioridade',$chamadoPrioridade);
	$tpl->assign('chamadoPrioridadeId',$chamadoPrioridadeID);
	$tpl->assign('chamadoDescricao',$chamadoDescricao);
	
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
		
		$tpl->newBlock('chamadoCategoriasTransf');
		
		$tpl->assign('categoriaId',$categoriaID);
		$tpl->assign('categoriaArea',$categoriaArea);
		$tpl->assign('categoriaNome',$categoriaNome);
	}
	
	$anexo = $chamadosDAO->anexoInfo($chamadoToken);	// Função responsável por buscar todos anexos vinculados ao chamado
	
	foreach($anexo as $key => $value){
		$anx = $anexo[$key];
		
		$anexoData		= $anx->getAnexoData();
		$anexoDiretorio	= $anx->getAnexoDiretorio();
		$anexoArquivo	= $anx->getAnexoArquivo();
		
		$tpl->newBlock('chamadoAnexo');
		
		$tpl->assign('chamadoAnexoData',$anexoData);
		$tpl->assign('chamadoAnexoDiretorio',$anexoDiretorio);
		$tpl->assign('chamadoAnexoArquivo',$anexoArquivo);
	}
	
	$comentario = $chamadosDAO->comentarioInfo($chamadoToken);	// Busca todos comentários vinculados ao chamado
	
	foreach($comentario as $key => $value){
		$cmt = $comentario[$key];
		
		$comentarioData		= $cmt->getComentarioData();
		$comentarioNome 	= $cmt->getComentarioNome();
		$comentarioTexto 	= $cmt->getComentarioTexto();
		$comentarioTipo		= $cmt->getComentarioTipo();
		
		$tpl->newBlock('chamadoComentario');
		
		$tpl->assign('comentarioData',$comentarioData);
		$tpl->assign('comentarioResponsavel',$comentarioNome);
		$tpl->assign('comentarioTexto',$comentarioTexto);
		if($comentarioTipo == 'S'){
			$tpl->assign('solucao','class="success"');
		}
	}

	$tpl->printToScreen();