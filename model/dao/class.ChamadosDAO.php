<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/model/inc/inc.config.php');
	
	/* 			FUNÇÕES DISPONÍVEIS
	//	-	chamadoCategorias()
	//		//	Lista as categorias disponíveis na tabela HELP_CATEGORIA.
	//
	//	-	chamadoList(string $username, int $tipo)
	//		//	Mostra os chamados que aparecem na tela inicial, tanto tech quanto user.
	//
	//	-	chamadoWaiting()
	//		//	Busca chamados à espera que aparecem na tela inicial do tecnico, separados por area.
	//
	//	-	chamadoInfo(int $chamadoID)
	//		//	Busca todas informações a respeito de um chamado específico. Utilizado na pagina acchamado.php
	//
	//	-	anexoInfo(int $chamadoID)
	//		//	Busca todos anexos presentes no chamado indicado.
	//
	//	-	comentarioInfo(int $chamadoID)
	//		//	Busca todos comentarios vinculados a um chamado especifico.
	//
	//	-	chamadoUpdate(int $chamadoID, array $chamadoObj)
	//		//	Atualiza as informações do chamado, feitas atraves da pagina acchamado.php
	//
	//	-	chamadoInsert(object $chamadoObj)
	//		//	Insere um novo chamado no banco de dados. Retorna o ID do novo chamado.
	//
	//	-	chamadoDone(int $chamadoID)
	//		//	Conclui o chamado.
	//
	//	-	chamadoOrder()	-	NÃO DEFINIDA.
	//		//	Responsável por ordenar os chamados.
	//
	//	-	comentarioInsert(int $chamadoID, string $comentarioTexto, string $comentarioNome)
	//		//	Insere um comentario no chamado.
	//
	//	-	anexoInsert(int $chamadoID, string $anexoArquivo, string $anexoNome)
	//		//	Insere um anexo ao chamado.
	//
	//	-	chamadoPick(int $chamadoID, string $acompanhamentoTecnico)
	//		//	Função chamada quando o técnico atribui o chamado a si.
	//
	//	-	chamadoRelease(int $chamadoID)
	//		//	Função chamada para desvincular o técnico do chamado.
	//
	//	-	chamadoSolution(int $chamadoID)
	//		//	Retorna os dados referentes à conclusão do chamado.
	//
	//	-	chamadoTransfere(int $chamadoID, object $chamadoObj)
	//		//	Transfere o chamado para outra área de atribuição após a conclusão do mesmo pelo técnico.
	//		//	Sem necessidade do usuário aprovar ou rejeitar a conclusão.
	//
	//	-	chamadoFinish(int $chamadoID)
	//		//	Encerra o chamado, atualizando seu status para 4 (ENCERRADO).
	//
	//	-	chamadoDecline(int $chamadoID)
	//		//	Reabre o chamado, caso o usuário rejeite sua conclusão.
	//		//	Altera seu status para 2 (ANDAMENTO), altera o status da solução para R (REJEITADA) e insere novo comentário.
	//
	//	-	avisoBusca()
	//		//	Busca se há algum aviso ativo no momento.
	//
	*/
	
	CLASS ChamadosDAO{
		
		PRIVATE $dba;
		
		PUBLIC FUNCTION ChamadosDAO(){
			$dba = NEW DbAdmin('oracle');
			$dba->connect();
			$this->dba = $dba;
		}
		
		//	***************************************************************
		//	Busca todas as categorias disponíveis na tabela HELP_CATEGORIA.
		//	Chamada por:
		//		controller/tech/acchamado.php
		//		controller/tech/novochamado.php
		//		controller/user/novochamado.php
		PUBLIC FUNCTION chamadoCategorias(){
			$dba = $this->dba;
			
			$sql = "SELECT	C.ID 		ID,
							C.DESCRICAO CATEGORIA,
							C.AREA		AREA
					FROM	HELP_CATEGORIA C
					ORDER BY C.ID";		
					
			$stmt = $dba->query($sql);
			$vet = ARRAY();
			
			$i = 0;
			WHILE(OCIFETCHINTO($stmt, $row, OCI_ASSOC)){
				$categoria = NEW Chamados();
				
				$categoriaID	=	$row['ID'];
				$categoriaNome	=	UTF8_ENCODE($row['CATEGORIA']);
				$categoriaArea	=	$row['AREA'];
				
				$categoria->setCategoriaID($categoriaID);
				$categoria->setCategoriaNome($categoriaNome);
				$categoria->setCategoriaArea($categoriaArea);
				
				$vet[$i] = $categoria;
				$i++;
			}
			RETURN $vet;
		}
		
		//	***************************************************************
		//	Busca todos chamados que devem aparecer na tela do usuário ou técnico.
		//	Caso seja usuário, serão mostrados apenas os chamados abertos por ele.
		//	Caso seja técnico, serão mostrados somente os chamados pelos quais é responsável.
		//	Chamada por:
		//		controller/tech/home.php
		//		controller/user/home.php
		PUBLIC FUNCTION chamadoList($username, $tipo, $filtro){
			$dba = $this->dba;
			IF($tipo == 0){		// Usuário. Busca chamados abertos pelo username.
				$where = " AND CH.USERNAME = UPPER('$username')
						   $filtro
						   ORDER BY 
								CH.STATUS DESC,
								CH.AREA ASC,
								CH.PRIORIDADE DESC,
								CH.TIPO ASC,
								(
								CASE 
								  WHEN VAT.DATA IS NULL THEN CH.DTABERTURA 
								  ELSE VAT.DATA 
								END
								) ASC,
								CH.DTABERTURA ASC";
			}ELSE IF($tipo == 1){ // Técnico. Busca chamados atribuidos.
				$where = "	
							AND (VACO.USERNAME = UPPER('$username') OR CH.USERNAME = UPPER('$username'))
							$filtro
							ORDER BY 
								CH.STATUS ASC,
								CH.PRIORIDADE DESC,
								CH.TIPO ASC,
								(
								CASE 
								  WHEN VAT.DATA IS NULL THEN CH.DTABERTURA 
								  ELSE VAT.DATA 
								END
								) ASC,
								CH.DTABERTURA ASC";
			}
			$sql = "SELECT  CH.IDCHAMADO ID,
							CH.TOKEN TOKEN,
							CH.TITULO TITULO,
							TO_CHAR(CH.DTABERTURA,'DD/MM/YYYY HH24:MI:SS') ABERTURA,
							TO_CHAR(VAT.DATA,'DD/MM/YYYY HH24:MI:SS') ATUALIZACAO,
							CH.NOMEREQUERENTE REQUERENTE,
							SE.SETOR SETOR,
							VACO.TECNICO TECNICO,
							DECODE(CH.PRIORIDADE, '1', 'Baixa',
												  '2', 'Media',
												  '3', 'Alta',
												  '4', 'Urgente') PRIORIDADE,
							DECODE(CH.TIPO, '1', 'Incidente',
											'2', 'Ajuste',
											'3', 'Solicitação',
											'4', 'Dúvida') TIPO,
							CAT.DESCRICAO CATEGORIA,
							CH.STATUS STATUSID,
							DECODE(CH.STATUS, '1', 'Aberto',
											  '2', 'Em análise',
											  '3', 'Concluído',
											  '4', 'Encerrado') STATUS,
							CH.DESCRICAO DESCRICAO
					FROM    HELP_CHAMADO CH 
								LEFT JOIN V_HELP_CHAMADO_ACOMPANHAMENTO VACO 
								ON CH.IDCHAMADO = VACO.IDCHAMADO 
								LEFT JOIN V_HELP_CHAMADO_ATUALIZACAO VAT 
								ON CH.IDCHAMADO = VAT.IDCHAMADO,
							HELP_CATEGORIA CAT,
							HELP_SETOR SE
					WHERE   CH.CATEGORIA = CAT.ID
					AND		CH.SETORREQUERENTE = SE.ID
					$where";
				// var_dump($sql);die;
			$stmt = $dba->query($sql);
			
			$vet = ARRAY();
			
			$i = 0;
			WHILE(OCIFETCHINTO($stmt, $row, OCI_ASSOC)){
				$chamado = NEW Chamados();
				
				$chamadoID				=	$row['ID'];
				$chamadoToken 			= 	$row['TOKEN'];
				$chamadoTitulo 			= 	UTF8_ENCODE($row['TITULO']);
				$chamadoAbertura 		= 	$row['ABERTURA'];
				$acompanhamentoData 	=	$row['ATUALIZACAO'];
				$chamadoNome 			= 	UTF8_ENCODE($row['REQUERENTE']);
				$chamadoSetor			=	UTF8_ENCODE($row['SETOR']);
				$acompanhamentoTecnico	= 	UTF8_ENCODE($row['TECNICO']);
				$chamadoPrioridade 		= 	$row['PRIORIDADE'];
				$chamadoTipo 			= 	$row['TIPO'];
				$chamadoCategoria 		= 	UTF8_ENCODE($row['CATEGORIA']);
				$chamadoStatus 			= 	$row['STATUS'];
				$chamadoStatusID		=	$row['STATUSID'];
				$chamadoDescricao 		= 	UTF8_ENCODE($row['DESCRICAO']);
				
				$chamado->setChamadoID($chamadoID);
				$chamado->setChamadoToken($chamadoToken);
				$chamado->setChamadoTitulo($chamadoTitulo);
				$chamado->setChamadoAbertura($chamadoAbertura);
				$chamado->setAcompanhamentoData($acompanhamentoData);
				$chamado->setChamadoNome($chamadoNome);
				$chamado->setChamadoSetor($chamadoSetor);
				$chamado->setChamadoPrioridade($chamadoPrioridade);
				$chamado->setChamadoTipo($chamadoTipo);
				$chamado->setChamadoStatus($chamadoStatus);
				$chamado->setChamadoStatusID($chamadoStatusID);
				$chamado->setChamadoDescricao($chamadoDescricao);
				$chamado->setChamadoCategoria($chamadoCategoria);
				$chamado->setAcompanhamentoTecnico($acompanhamentoTecnico);
				
				$vet[$i] = $chamado;
				$i++;
			}
			RETURN $vet;
			
		}
		
		//	***************************************************************
		//	Lista dos chamados que deverão aparecer na lista de espera, separados
		//	por área de atuação.
		//	Chamada por:
		//		controller/tech/home.php
		PUBLIC FUNCTION chamadoWaiting($filtro){
			$dba = $this->dba;
			
			if($filtro == ""){
				$filtro = "AND    	(CH.STATUS != '3' OR (CH.STATUS = '3' AND SYSDATE-CH.DTCONCLUSAO < 10))";
			}
			
			$sql = "SELECT  CH.IDCHAMADO ID,
							CH.TOKEN TOKEN,
							CH.TITULO TITULO,
							TO_CHAR(CH.DTABERTURA,'DD/MM/YYYY HH24:MI:SS') ABERTURA,
							TO_CHAR(VAT.DATA,'DD/MM/YYYY HH24:MI:SS') ATUALIZACAO,
							CH.NOMEREQUERENTE REQUERENTE,
							SE.SETOR SETOR,
							DECODE(CH.PRIORIDADE, '1', 'Baixa',
												  '2', 'Media',
												  '3', 'Alta',
												  '4', 'Urgente') PRIORIDADE,
							DECODE(CH.TIPO, '1', 'Incidente',
											'2', 'Ajuste',
											'3', 'Solicitação',
											'4', 'Dúvida') TIPO,
							CH.STATUS STATUSID,
							DECODE(CH.STATUS, '1', 'Aberto',
											  '2', 'Em análise',
											  '3', 'Concluído',
											  '4', 'Encerrado') STATUS,
							CH.DESCRICAO DESCRICAO,
							CAT.DESCRICAO CATEGORIA,
							VACO.TECNICO TECNICO,
							CH.AREA AREA
					FROM    HELP_CHAMADO CH 
								LEFT JOIN V_HELP_CHAMADO_ACOMPANHAMENTO VACO 
									ON CH.IDCHAMADO = VACO.IDCHAMADO 
								LEFT JOIN V_HELP_CHAMADO_ATUALIZACAO VAT 
									ON CH.IDCHAMADO = VAT.IDCHAMADO,
							HELP_CATEGORIA CAT,
							HELP_SETOR SE
				  WHERE   	CH.CATEGORIA = CAT.ID
				  AND		CH.SETORREQUERENTE = SE.ID
				  AND		CH.STATUS != '4'
							$filtro
				  ORDER BY 
							CH.STATUS ASC,
							CH.PRIORIDADE DESC,
							CH.TIPO ASC,
							(
							CASE 
							  WHEN VAT.DATA IS NULL THEN CH.DTABERTURA 
							  ELSE VAT.DATA 
							END
							) ASC,
							CH.DTABERTURA ASC";
			$stmt = $dba->query($sql);
			$vet = ARRAY();
			
			$i = 0;
			WHILE(OCIFETCHINTO($stmt, $row, OCI_ASSOC)){
				$chamado = NEW Chamados();
				
				$chamadoID				=	$row['ID'];
				$chamadoToken 			= 	$row['TOKEN'];
				$chamadoTitulo 			= 	UTF8_ENCODE($row['TITULO']);
				$chamadoAbertura 		= 	$row['ABERTURA'];
				$acompanhamentoData 	=	$row['ATUALIZACAO'];
				$chamadoNome 			= 	UTF8_ENCODE($row['REQUERENTE']);
				$chamadoSetor			=	UTF8_ENCODE($row['SETOR']);
				$acompanhamentoTecnico	= 	UTF8_ENCODE($row['TECNICO']);
				$chamadoPrioridade 		= 	$row['PRIORIDADE'];
				$chamadoTipo 			= 	$row['TIPO'];
				$chamadoCategoria 		= 	UTF8_ENCODE($row['CATEGORIA']);
				$chamadoStatus 			= 	$row['STATUS'];
				$chamadoStatusID		=	$row['STATUSID'];
				$chamadoDescricao 		= 	UTF8_ENCODE($row['DESCRICAO']);
				$chamadoArea			=	$row['AREA'];
				
				$chamado->setChamadoID($chamadoID);
				$chamado->setChamadoToken($chamadoToken);
				$chamado->setChamadoTitulo($chamadoTitulo);
				$chamado->setChamadoAbertura($chamadoAbertura);
				$chamado->setAcompanhamentoData($acompanhamentoData);
				$chamado->setChamadoNome($chamadoNome);
				$chamado->setChamadoSetor($chamadoSetor);
				$chamado->setAcompanhamentoTecnico($acompanhamentoTecnico);
				$chamado->setChamadoPrioridade($chamadoPrioridade);
				$chamado->setChamadoTipo($chamadoTipo);
				$chamado->setChamadoCategoria($chamadoCategoria);
				$chamado->setChamadoStatus($chamadoStatus);
				$chamado->setChamadoStatusID($chamadoStatusID);
				$chamado->setChamadoDescricao($chamadoDescricao);
				$chamado->setChamadoArea($chamadoArea);
				
				$vet[$i] = $chamado;
				$i++;
			}
			RETURN $vet;
			
		}
		
		//	***************************************************************
		//	Busca todas as informações referentes ao chamado para exibir na
		//	página de acompanhamento de chamado.
		//	Chamada por:
		//		controller/tech/acchamado.php
		//		controller/user/acchamado.php
		PUBLIC FUNCTION chamadoInfo($chamadoToken){
			$dba = $this->dba;
			
			$sqlChamado = "	SELECT  CH.TITULO TITULO,
									CH.IDCHAMADO ID,
									CH.STATUS STATUSID,
									DECODE(CH.STATUS, '1', 'Aberto',
											  '2', 'Em análise',
											  '3', 'Concluído',
											  '4', 'Encerrado') STATUS,
									VACO.TECNICO TECNICO,
									CH.NOMEREQUERENTE REQUERENTE,
									EMP.EMPRESA EMPRESA,
									FIL.FILIAL FILIAL,
									SE.SETOR SETOR,
									TO_CHAR(CH.DTABERTURA,'DD/MM/YYYY HH24:MI:SS') ABERTURA,
									TO_CHAR(VAT.DATA,'DD/MM/YYYY HH24:MI:SS') ATUALIZACAO,
									CH.TIPO TIPOID,
									DECODE(CH.TIPO, '1', 'Incidente',
													'2', 'Ajuste',
													'3', 'Solicitação',
													'4', 'Dúvida') TIPO,
									CH.AREA AREAID,
									DECODE(CH.AREA, '1', 'Infra-estrutura',
													'2', 'Sistemas',
													'3', 'Telefonia') AREA,
									CH.CATEGORIA CATEGORIAID,
									CAT.DESCRICAO CATEGORIA,
									CH.PRIORIDADE PRIORIDADEID,
									DECODE(CH.PRIORIDADE, '1', 'Baixa',
														  '2', 'Media',
														  '3', 'Alta',
														  '4', 'Urgente') PRIORIDADE,
									CH.DESCRICAO DESCRICAO,
									CH.USERNAME USERNAME
							FROM    HELP_CHAMADO CH 
										LEFT JOIN V_HELP_CHAMADO_ACOMPANHAMENTO VACO 
										   ON CH.IDCHAMADO = VACO.IDCHAMADO 
										LEFT JOIN V_HELP_CHAMADO_ATUALIZACAO VAT 
										   ON CH.IDCHAMADO = VAT.IDCHAMADO,
									HELP_CATEGORIA CAT,
									HELP_EMPRESA EMP,
									HELP_FILIAL FIL,
									HELP_SETOR SE
							WHERE   CH.CATEGORIA = CAT.ID
							AND		CH.SETORREQUERENTE = SE.ID
							AND    	CH.EMPRESA = EMP.ID
							AND    	CH.FILIAL = FIL.ID
							AND     CH.TOKEN = '".$chamadoToken."'";

			$stmtChamado = $dba->query($sqlChamado);
			WHILE(OCIFETCHINTO($stmtChamado, $row, OCI_ASSOC)){
				$chamado = NEW Chamados();
				
				$chamadoTitulo 			= UTF8_ENCODE($row['TITULO']);
				$chamadoID 				= $row['ID'];
				$chamadoStatusID		= $row['STATUSID'];
				$chamadoStatus 			= $row['STATUS'];
				$acompanhamentoTecnico 	= UTF8_ENCODE($row['TECNICO']);
				$chamadoNome 			= UTF8_ENCODE($row['REQUERENTE']);
				$chamadoEmpresa			= UTF8_ENCODE($row['EMPRESA']);
				$chamadoFilial			= UTF8_ENCODE($row['FILIAL']);
				$chamadoSetor			= UTF8_ENCODE($row['SETOR']);
				$chamadoAbertura 		= $row['ABERTURA'];
				$acompanhamentoData 	= $row['ATUALIZACAO'];
				$chamadoTipoID 			= $row['TIPOID'];
				$chamadoTipo 			= $row['TIPO'];
				$chamadoAreaID			= $row['AREAID'];
				$chamadoArea 			= $row['AREA'];
				$chamadoCategoriaID 	= $row['CATEGORIAID'];
				$chamadoCategoria 		= UTF8_ENCODE($row['CATEGORIA']);
				$chamadoPrioridadeID 	= $row['PRIORIDADEID'];
				$chamadoPrioridade 		= $row['PRIORIDADE'];
				$chamadoDescricao 		= UTF8_ENCODE($row['DESCRICAO']);
				$chamadoUsername		= UTF8_ENCODE($row['USERNAME']);
				
				$chamado->setChamadoID($chamadoID);
				$chamado->setChamadoTitulo($chamadoTitulo);
				$chamado->setChamadoStatusID($chamadoStatusID);
				$chamado->setChamadoStatus($chamadoStatus);
				$chamado->setAcompanhamentoTecnico($acompanhamentoTecnico);
				$chamado->setChamadoNome($chamadoNome);
				$chamado->setChamadoEmpresa($chamadoEmpresa);
				$chamado->setChamadoFilial($chamadoFilial);
				$chamado->setChamadoSetor($chamadoSetor);
				$chamado->setChamadoAbertura($chamadoAbertura);
				$chamado->setAcompanhamentoData($acompanhamentoData);
				$chamado->setChamadoTipoID($chamadoTipoID);
				$chamado->setChamadoTipo($chamadoTipo);
				$chamado->setChamadoAreaID($chamadoAreaID);
				$chamado->setChamadoArea($chamadoArea);
				$chamado->setChamadoCategoriaID($chamadoCategoriaID);
				$chamado->setChamadoCategoria($chamadoCategoria);
				$chamado->setChamadoPrioridadeID($chamadoPrioridadeID);
				$chamado->setChamadoPrioridade($chamadoPrioridade);
				$chamado->setChamadoDescricao($chamadoDescricao);
				$chamado->setChamadoUsername($chamadoUsername);
				
			}
			
			RETURN $chamado;
		}
		
		//	***************************************************************
		//	Busca todos anexos vinculados ao chamado indicado.
		//	Chamada por:
		//		controller/tech/acchamado.php
		//		controller/user/acchamado.php
		PUBLIC FUNCTION anexoInfo($chamadoToken){
			$dba = $this->dba;
			
			$sqlAnexo = "	SELECT  TO_CHAR(ANX.DTANEXO,'DD/MM/YYYY HH24:MI:SS') DTANEXO,
									CONCAT(ANX.DIRETORIO, ANX.ANEXO) ENDERECO,
									ANX.ANEXO ARQUIVO
							FROM    HELP_CHAMADO_ANEXO ANX
							WHERE   ANX.IDCHAMADO = (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."')
							ORDER BY ANX.DTANEXO DESC";
							
			$vet = ARRAY();
			
			$stmtAnexo = $dba->query($sqlAnexo);
			$i = 0;
			
			WHILE(OCIFETCHINTO($stmtAnexo, $row, OCI_ASSOC)){
				$chamado = NEW Chamados();
				
				$anexoData		= $row['DTANEXO'];
				$anexoDiretorio = UTF8_ENCODE($row['ENDERECO']);
				$anexoArquivo	= UTF8_ENCODE($row['ARQUIVO']);
				
				$chamado->setAnexoData($anexoData);
				$chamado->setAnexoDiretorio($anexoDiretorio);
				$chamado->setAnexoArquivo($anexoArquivo);
				
				$vet[$i] = $chamado;
				$i++;
			}
			
			RETURN $vet;
			
		}
		
		//	***************************************************************
		//	Busca todos comentários vinculados ao chamado.
		//	Chamada por:
		//		controller/tech/acchamado.php
		//		controller/user/acchamado.php
		PUBLIC FUNCTION comentarioInfo($chamadoToken){
			$dba = $this->dba;
			
			$sqlComentario = "	SELECT  TO_CHAR(VCOM.DTCOMENTARIO,'DD/MM/YYYY HH24:MI:SS') DTCOMENTARIO,
										VCOM.USUARIO,
										VCOM.COMENTARIO,
										VCOM.TIPO TIPO
								FROM    V_HELP_CHAMADO_COMENTARIO VCOM
								WHERE   IDCHAMADO = (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."')
								ORDER BY	VCOM.DTCOMENTARIO DESC";
			$vet = ARRAY();
							
			$stmtComentario = $dba->query($sqlComentario);
			$i = 0;
			WHILE(OCIFETCHINTO($stmtComentario, $row, OCI_ASSOC)){
				$chamado = NEW Chamados();
				
				$comentarioData 	= $row['DTCOMENTARIO'];
				$comentarioNome 	= UTF8_ENCODE($row['USUARIO']);
				$comentarioTexto	= UTF8_ENCODE($row['COMENTARIO']);
				$comentarioTipo		= $row['TIPO'];
				
				$chamado->setComentarioData($comentarioData);
				$chamado->setComentarioNome($comentarioNome);
				$chamado->setComentarioTexto($comentarioTexto);
				$chamado->setComentarioTipo($comentarioTipo);
				
				$vet[$i] = $chamado;
				$i++;
			}
			
			RETURN $vet;
			
		}
		
		//	***************************************************************
		//	Atualiza as informações do chamado, caso estas sejam inseridas
		//	erroneamente pelo usuário.
		//	Atualiza TIPO, AREA, CATEGORIA e PRIORIDADE.
		//	Chamada por:
		//		controller/tech/ajax/editarChamado.php	<<	assets/scripts/editaChamado.js
		PUBLIC FUNCTION chamadoUpdate($chamadoToken, $chamadoObj){
			$dba = $this->dba;
			
			$chamadoTipo 		= 	$chamadoObj->getChamadoTipo();
			$chamadoArea 		= 	$chamadoObj->getChamadoArea();
			$chamadoCategoria 	= 	$chamadoObj->getChamadoCategoria();
			$chamadoPrioridade 	= 	$chamadoObj->getChamadoPrioridade();
			
			$sql = "UPDATE	HELP_CHAMADO
					SET		TIPO = '".$chamadoTipo."',
							AREA = '".$chamadoArea."',
							CATEGORIA = '".$chamadoCategoria."',
							PRIORIDADE = '".$chamadoPrioridade."'
					WHERE	TOKEN = '".$chamadoToken."'";
					
			$logDAO = NEW LogDAO();
			$logDAO->logInsert("Realizou alterações no chamado. TIPO(".$chamadoTipo.") - AREA(".$chamadoArea.") - CATEGORIA(".$chamadoCategoria.") - PRIORIDADE(".$chamadoPrioridade.")", $chamadoToken);
					
			IF($dba->query($sql)){	RETURN TRUE;	}
			ELSE{	RETURN FALSE;	}
		}
		
		//	***************************************************************
		//	Insere um novo chamado no banco de dados.
		//	Chamada por:
		//		controller/tech/insereChamado.php
		//		controller/user/insereChamado.php
		PUBLIC FUNCTION chamadoInsert($chamadoObj){
			$dba = $this->dba;
			
			$chamadoEmpresa		=	$chamadoObj->getChamadoEmpresa();
			$chamadoFilial		=	$chamadoObj->getChamadoFilial();
			$chamadoNome		= 	STR_REPLACE("'","", STRIPSLASHES($chamadoObj->getChamadoNome()));
			$chamadoSetor		=	$chamadoObj->getChamadoSetor();
			$chamadoTipo		=	$chamadoObj->getChamadoTipo();
			$chamadoArea		=	$chamadoObj->getChamadoArea();
			$chamadoCategoria	=	$chamadoObj->getChamadoCategoria();
			$chamadoPrioridade	=	$chamadoObj->getChamadoPrioridade();
			$chamadoTitulo		=	STR_REPLACE("'","", STRIPSLASHES($chamadoObj->getChamadoTitulo()));
			$chamadoDescricao	=	STR_REPLACE("'","", STRIPSLASHES($chamadoObj->getChamadoDescricao()));
			$chamadoAbertura	=	DATE("d/m/Y H:i:s");
			$chamadoUsername	=	STR_REPLACE("'","", STRIPSLASHES($chamadoObj->getChamadoUsername()));
			
			$sqlID = "	SELECT	SEQ_HELP_CHAMADO.NEXTVAL IDCHAMADO
						FROM	DUAL";
			$stmtID = $dba->query($sqlID);
			$res = OCI_FETCH_ARRAY($stmtID, OCI_ASSOC);
			$chamadoID = $res['IDCHAMADO'];
			
			$sql = "INSERT INTO HELP_CHAMADO (IDCHAMADO, TOKEN, EMPRESA, FILIAL, NOMEREQUERENTE, IPREQUERENTE, SETORREQUERENTE, TIPO, AREA, CATEGORIA, PRIORIDADE, TITULO, DESCRICAO, DTABERTURA, STATUS, USERNAME)
					VALUES 	(".$chamadoID.", 
							'".SHA1($chamadoID)."',
							(SELECT ID FROM HELP_EMPRESA WHERE UPPER(EMPRESA) = UPPER('".$chamadoEmpresa."')), 
							(SELECT ID FROM HELP_FILIAL WHERE UPPER(FILIAL) = UPPER('".$chamadoFilial."')), 
							'".UTF8_DECODE($chamadoNome)."', 
							'".$_SERVER['REMOTE_ADDR']."', 
							(SELECT ID FROM HELP_SETOR WHERE UPPER(SETOR) = UPPER('".$chamadoSetor."')), 
							'".$chamadoTipo."', 
							'".$chamadoArea."', 
							'".$chamadoCategoria."', 
							'".$chamadoPrioridade."', 
							'".UTF8_DECODE($chamadoTitulo)."', 
							'".UTF8_DECODE($chamadoDescricao)."', 
							TO_DATE('".$chamadoAbertura."','DD/MM/YYYY HH24:MI:SS'), 
							'1', 
							UPPER('".$chamadoUsername."')
							)";
			
			IF($dba->query($sql)){
				$logDAO = NEW LogDAO();
				$logDAO->logInsert("Abriu um novo chamado. TIPO(".$chamadoTipo.") - AREA(".$chamadoArea.") - CATEGORIA(".$chamadoCategoria.") - PRIORIDADE(".$chamadoPrioridade.")", SHA1($chamadoID));
				return SHA1($chamadoID);
			}ELSE{	
				RETURN FALSE;	
			}
		}
		
		//	***************************************************************
		//	Quando o chamado é concluído pelo técnico, esta função é chamada
		//	para alterar o status do chamado para C (Concluído).
		//	Chamada por:
		//		controller\tech\ajax\atribuirChamado.php	<<	assets\scripts\pickChamado.js
		PUBLIC FUNCTION chamadoDone($chamadoObj){
			$dba = $this->dba;
			
			$chamadoToken	= $chamadoObj->getChamadoToken();
			$solucaoNome 	= STR_REPLACE("'","", STRIPSLASHES($chamadoObj->getSolucaoNome()));
			$solucaoTexto 	= STR_REPLACE("'","", STRIPSLASHES($chamadoObj->getSolucaoTexto()));
			$solucaoUsername = STR_REPLACE("'","", STRIPSLASHES($chamadoObj->getChamadoUsername()));
			$solucaoData 	= DATE("d/m/Y H:i:s");
			$checksum 		= 0;
			
			$solutionSql = "INSERT INTO HELP_CHAMADO_SOLUCAO(IDCHAMADOSOLUCAO, IDCHAMADO, TECNICO, DATAHORASOLUCAO, SOLUCAO, RANKSOLUCAO, USERNAME, STATUS)
							VALUES (SEQ_HELP_CHAMADO_SOLUCAO.NEXTVAL, (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."'), '".UTF8_DECODE($solucaoNome)."', TO_DATE('".$solucaoData."','DD/MM/YYYY HH24:MI:SS'), '".UTF8_DECODE($solucaoTexto)."', 1, UPPER('".$solucaoUsername."'), 'S')";
			
			IF($dba->query($solutionSql)){	$checksum++;	}
			
			$chamadoSql = "	UPDATE	HELP_CHAMADO
							SET		STATUS = '3',
									DTCONCLUSAO = TO_DATE('".$solucaoData."','DD/MM/YYYY HH24:MI:SS')
							WHERE	TOKEN = '".$chamadoToken."'";
							
			IF($dba->query($chamadoSql)){	$checksum++;	}
							
			IF($checksum == 2){	
				$logDAO = NEW LogDAO();
				$logDAO->logInsert("Concluiu o chamado: ".$solucaoTexto, $chamadoToken);
				RETURN TRUE;	
			}
			else IF($checksum == 1){	/*OCI_ROLLBACK;*/	RETURN FALSE;	}
			ELSE{	RETURN FALSE;	}
		}
		
		//	***************************************************************
		//	Ordena os chamados.
		//	A definir.
		PUBLIC FUNCTION chamadoOrder(){
			// A definir.
		}
		
		//	***************************************************************
		//	Insere comentário ao chamado. 
		//	Chamada por:
		//		controller\ajax\inserirComentario.php	<<	assets\scripts\interactionFunctions.js	<<	assets\scripts\comentarioInsert.js
		PUBLIC FUNCTION comentarioInsert($chamadoToken, $comentarioTexto, $comentarioNome){
			$dba = $this->dba;
			
			$comentarioData = DATE("d/m/Y H:i:s");
			
			$sql = "INSERT INTO HELP_CHAMADO_COMENTARIO (IDCHAMADOCOMENTARIO, IDCHAMADO, COMENTARIO, USUARIO, DTCOMENTARIO) VALUES
					(SEQ_HELP_CHAMADO_COMENTARIO.NEXTVAL, (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."'), '".STR_REPLACE("'","", STRIPSLASHES(UTF8_DECODE($comentarioTexto)))."', '".UTF8_DECODE($comentarioNome)."', TO_DATE('".$comentarioData."','DD/MM/YYYY HH24:MI:SS'))";
					
			IF($dba->query($sql)){	
				$logDAO = NEW LogDAO();
				$logDAO->logInsert("Inseriu um novo comentário ao chamado", $chamadoToken);
				RETURN TRUE;	
			}
			ELSE{	RETURN FALSE;	}
		}
		
		//	***************************************************************
		//	Insere anexo ao chamado.
		//	O anexo é armazenado em uma pasta específica, contendo a hash SHA1 (token) do chamado,
		//	para fins de organização.
		//	Chamada por:
		//		controller\tech\insereChamado.php
		//		controller\user\insereChamado.php
		//		controller\attachmentInsert.php	<<	assets\scripts\interactionFunctions.js	<<	assets\scripts\comentarioInsert.js
		PUBLIC FUNCTION anexoInsert($chamadoToken, $chamadoAnexo, $chamadoNome){
			$dba = $this->dba;
			
			$anexoData = DATE("d/m/Y H:i:s");
			$chk = 0;
			
			$validExtensions = ARRAY(	'PNG','JPG','JPEG',
										'XLS','XLSX','DOC','DOCX','PPT','PPTX',
										'XML','PDF','TXT','ZIP','RAR','RET','REM','HTML','LOG');
			$invalidFiles = ARRAY();
			$invalidIndex = 0;
			
			$total = COUNT($chamadoAnexo['name']);
			$uploads_dir = "../../uploads/".$chamadoToken;
			$index = "../../uploads/uploadindex.php";
			$dest = $uploads_dir."/index.php";
			
			IF($total>0 && $chamadoAnexo['tmp_name'][0]!=""){
				$files = $chamadoAnexo;
				FOR($i=0; $i<$total; $i++){
					IF($error==UPLOAD_ERR_OK){
						IF(IN_ARRAY((PATHINFO(strtoupper($chamadoAnexo['name'][$i]), PATHINFO_EXTENSION)), $validExtensions)){
							IF($chamadoAnexo['size'][$i] < 10240000){
								IF(!FILE_EXISTS($uploads_dir)){
									MKDIR($uploads_dir, 0777, TRUE);
									COPY($index, $dest);
								}
								$copy = 1;
								$chamados = NEW Chamados();
								$tmp_name = UTF8_DECODE($chamadoAnexo['tmp_name'][$i]);
								$name = STR_REPLACE("'","", STRIPSLASHES(UTF8_DECODE($chamadoAnexo['name'][$i])));
								$copyname = $name;
								WHILE(FILE_EXISTS($uploads_dir."/".$copyname)){
									$copyname = $copy."_".$name;
									$copy++;
								}
								MOVE_UPLOADED_FILE($tmp_name, "$uploads_dir/$copyname");
								
								$sql = "INSERT INTO HELP_CHAMADO_ANEXO (IDCHAMADOANEXO, IDCHAMADO, ANEXO, DIRETORIO, USUARIO, DTANEXO) VALUES
										(SEQ_HELP_CHAMADO_ANEXO.NEXTVAL, (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."'), '".$copyname."', '".$uploads_dir."/', '".$chamadoNome."', TO_DATE('".$anexoData."','DD/MM/YYYY HH24:MI:SS'))";
										
								IF($dba->query($sql)){	$chk++;	}
							}ELSE{
								$invalidFiles[$invalidIndex] = ARRAY(	'f' => STR_REPLACE("'","", STRIPSLASHES(UTF8_DECODE($chamadoAnexo['name'][$i]))),
																		'e' => 2);
								$invalidIndex++;
							}	
						}ELSE{
							$invalidFiles[$invalidIndex] = ARRAY(	'f' => STR_REPLACE("'","", STRIPSLASHES(UTF8_DECODE($chamadoAnexo['name'][$i]))),
																	'e' => 1);
							$invalidIndex++;
						}
					}
				}
			}
			if($chk > 0){
				$logDAO = NEW LogDAO();
				$logDAO->logInsert("Inseriu ".$chk." novo(s) anexo(s) ao chamado.", $chamadoToken);
			}
			
			$retornoAnexo = ARRAY(	'chk' => $chk,
									'f' => $invalidFiles);
			RETURN $retornoAnexo;
		}
		
		//	***************************************************************
		//	Utilizada para o técnico atribuir o chamado para si.
		//	Possui uma variável CHECKSUM. O banco de dados somente será atualizado
		//	caso o CHECKSUM seja igual a 2, se não, as alterações devem ser revertidas.
		//	Chamada por:
		//		controller\tech\ajax\atribuirChamado.php	<<	assets\scripts\pickChamado.js
		PUBLIC FUNCTION chamadoPick($chamadoToken, $acompanhamentoTecnico, $acompanhamentoUsername){
			$dba = $this->dba;
			
			$checksum = 0;
			$acompanhamentoData = DATE("d/m/Y H:i:s");
			
			$acompanhamentoSql = "	INSERT INTO HELP_CHAMADO_ACOMPANHAMENTO (IDCHAMADOACOMP, IDCHAMADO, TECNICO, DTACOMPANHAMENTO, USERNAME) VALUES
									(SEQ_HELP_CHAMADO_ACOMP.NEXTVAL, (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."'), '".$acompanhamentoTecnico."', TO_DATE('".$acompanhamentoData."','DD/MM/YYYY HH24:MI:SS'), UPPER('".$acompanhamentoUsername."'))";
							
			IF($dba->query($acompanhamentoSql)){	$checksum++;	}
			
			$chamadoSql = "	UPDATE 	HELP_CHAMADO
							SET		STATUS = '2'
							WHERE	TOKEN = '".$chamadoToken."'";
							
			IF($dba->query($chamadoSql)){	$checksum++;	}
			
			IF($checksum == 2){	
				$logDAO = NEW LogDAO();
				$logDAO->logInsert("Tornou-se responsável pelo atendimento do chamado", $chamadoToken);
				RETURN TRUE;	
			}
			else IF($checksum == 1){	/*OCI_ROLLBACK;*/	RETURN FALSE;	}
			ELSE{	RETURN FALSE;	}
		}
		
		//	***************************************************************
		//	Utilizada para o técnico liberar o chamado, no caso de tê-lo
		//	atribuído acidentalmente ou caso necessite trocar o técnico.
		//	Chamada por:
		//		controller\tech\ajax\atribuirChamado.php	<<	assets\scripts\pickChamado.js
		PUBLIC FUNCTION chamadoRelease($chamadoToken){
			$dba = $this->dba;
			
			$checksum = 0;
			$acompanhamentoData = DATE("d/m/Y H:i:s");
			
			$acompanhamentoSql = "	INSERT INTO HELP_CHAMADO_ACOMPANHAMENTO (IDCHAMADOACOMP, IDCHAMADO, DTACOMPANHAMENTO) VALUES
									(SEQ_HELP_CHAMADO_ACOMP.NEXTVAL, (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."'), TO_DATE('".$acompanhamentoData."','DD/MM/YYYY HH24:MI:SS'))";
							
			IF($dba->query($acompanhamentoSql)){	$checksum++;	}
			
			$chamadoSql = "	UPDATE 	HELP_CHAMADO
							SET		STATUS = '1'
							WHERE	TOKEN = '".$chamadoToken."'";
							
			IF($dba->query($chamadoSql)){	$checksum++;	}
			
			IF($checksum == 2){	
				$logDAO = NEW LogDAO();
				$logDAO->logInsert("Deixou o chamado à disposição de outro técnico", $chamadoToken);
				RETURN TRUE;	
			}
			else IF($checksum == 1){	/*OCI_ROLLBACK;*/	RETURN FALSE;	}
			ELSE{	RETURN FALSE;	}
		}
		
		//	***************************************************************
		//	Função responsável por retornar os dados referentes à solução do
		//	chamado. São exibidas na página de acompanhamento do usuário, onde
		//	o mesmo pode aprovar/rejeitar a conclusão do chamado.
		//	Chamada por:
		//		controller/user/acchamado.php
		PUBLIC FUNCTION chamadoSolution($chamadoToken){
			$dba = $this->dba;
			
			$sql = "SELECT  ROWNUM,
							CH.IDCHAMADOSOLUCAO,
							CH.TECNICO,
							CH.DATAHORASOLUCAO,
							CH.SOLUCAO
					FROM    (SELECT	IDCHAMADOSOLUCAO,
									TECNICO,
									TO_CHAR(DATAHORASOLUCAO,'DD/MM/YYYY HH24:MI:SS') DATAHORASOLUCAO,
									SOLUCAO
							 FROM   HELP_CHAMADO_SOLUCAO
							 WHERE  IDCHAMADO = (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."')
							 AND	STATUS = 'S'
							 ORDER BY DATAHORASOLUCAO DESC) CH
					WHERE ROWNUM = 1";
					
			$stmt = $dba->query($sql);
			
			$row = OCI_FETCH_ARRAY($stmt, OCI_ASSOC);
			
			$solucao = NEW Chamados();
			
			$solucaoID 		= $row['IDCHAMADOSOLUCAO'];
			$solucaoNome 	= UTF8_ENCODE($row['TECNICO']);
			$solucaoData 	= $row['DATAHORASOLUCAO'];
			$solucaoTexto 	= UTF8_ENCODE($row['SOLUCAO']);
			
			$solucao->setSolucaoID($solucaoID);
			$solucao->setSolucaoNome($solucaoNome);
			$solucao->setSolucaoData($solucaoData);
			$solucao->setSolucaoTexto($solucaoTexto);
			
			RETURN $solucao;
			
		}
		
		//	***************************************************************
		//	Requerida assim que um chamado for transferido pelo técnico.
		//	Altera o status do chamado para 1 (aberto), remove a data de
		//	conclusão anterior, adiciona uma nova linha na tabela de acom-
		//	panhamento do chamado e adiciona um novo comentário informando
		//	a transferência do chamado.
		//	Chamada por:
		//		controller\tech\ajax\transfereChamado.php	<<	assets\scripts\transfereChamado.js
		PUBLIC FUNCTION chamadoTransfere($chamadoToken, $chamadoObj){
			$dba = $this->dba;
			
			$dataAtual = DATE("d/m/Y H:i:s");
			
			$chamadoAreaID		= $chamadoObj->getChamadoAreaID();
			$chamadoCategoriaID	= $chamadoObj->getChamadoCategoriaID();
			$chamadoArea		= $chamadoObj->getChamadoArea();
			$chamadoCategoria	= $chamadoObj->getChamadoCategoria();
			
			$mensagem = "Chamado transferido para área responsável por ".$chamadoArea." (".$chamadoCategoria."). Aguardando atendimento.";
			
			//	Script para realizar a alteração dos dados do chamado.
			$sqlUpdate = "	UPDATE  HELP_CHAMADO
							SET     AREA = '".$chamadoAreaID."',
									CATEGORIA = '".$chamadoCategoriaID."',
									STATUS = '1',
									DTCONCLUSAO = ''
							WHERE   TOKEN = '".$chamadoToken."'";
							
							
			//	Script para inserir nova linha de acompanhamento.
			$sqlRelease = "	INSERT INTO HELP_CHAMADO_ACOMPANHAMENTO (IDCHAMADOACOMP, IDCHAMADO, DTACOMPANHAMENTO) VALUES
							(SEQ_HELP_CHAMADO_ACOMP.NEXTVAL, (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."'), TO_DATE('".$dataAtual."','DD/MM/YYYY HH24:MI:SS'))";
							
			//	Script para inserir novo comentário informando o usuário
			//	através da tabela de comentários da mudança de categoria.
			$sqlInforma = "	INSERT INTO HELP_CHAMADO_COMENTARIO (IDCHAMADOCOMENTARIO, IDCHAMADO, COMENTARIO, USUARIO, DTCOMENTARIO) VALUES
							(SEQ_HELP_CHAMADO_COMENTARIO.NEXTVAL, (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."'), '".UTF8_DECODE($mensagem)."', 'Helpdesk', TO_DATE('".$dataAtual."','DD/MM/YYYY HH24:MI:SS'))";
							
			IF($dba->query($sqlUpdate)){
				IF($dba->query($sqlRelease)){
					IF($dba->query($sqlInforma)){
						$logDAO = NEW LogDAO();
						$logDAO->logInsert("Transferiu o chamado para ÁREA(".$chamadoAreaID."), CATEGORIA(".$chamadoCategoriaID.")", $chamadoToken);
						RETURN TRUE;
					}ELSE{	RETURN FALSE;	}
				}ELSE{	RETURN FALSE;	}
			}ELSE{	RETURN FALSE;	}
		}
		
		//	***************************************************************
		//	Encerra o chamado. É chamada quando o usuário aprova a conclusão
		//	ou passa-se o tempo limite de aprovação.
		//	Chamada por:
		//		controller/user/ajax/aprovarChamado.php	<<	assets/scripts/aprovaChamado.js
		PUBLIC FUNCTION chamadoFinish($chamadoToken){
			$dba = $this->dba;
			
			$sqlChamado = "	UPDATE	HELP_CHAMADO
							SET		STATUS = '4'
							WHERE	TOKEN = '".$chamadoToken."'";
					
			IF($dba->query($sqlChamado)){	
				$logDAO = NEW LogDAO();
				$logDAO->logInsert("Aprovou a conclusão do chamado", $chamadoToken);
				RETURN TRUE;	
			}
			ELSE{	RETURN FALSE;	}
		}
		
		//	***************************************************************
		//	Altera o status do chamado para 2 (ANDAMENTO), o status da conclusão
		//	para R (REJEITADA) e insere um novo comentário explicando os motivos
		//	da rejeição da conclusão pelo usuário.
		//	Chamada por:
		//		controller/user/ajax/aprovarChamado.php	<<	assets/scripts/aprovaChamado.js
		PUBLIC FUNCTION chamadoDecline($chamadoObj){
			$dba = $this->dba;
			
			$dataAtual = DATE("d/m/Y H:i:s");
			
			$chamadoToken = $chamadoObj->getChamadoToken();
			$comentarioTexto = STR_REPLACE("'","", STRIPSLASHES($chamadoObj->getComentarioTexto()));
			$comentarioNome = STR_REPLACE("'","", STRIPSLASHES($chamadoObj->getComentarioNome()));
			$solucaoID = $chamadoObj->getSolucaoID();
			
			$sqlChamado = "	UPDATE	HELP_CHAMADO
							SET		STATUS = '2',
									DTCONCLUSAO = ''
							WHERE	TOKEN = '".$chamadoToken."'";
							
			$sqlSolucao = "	UPDATE	HELP_CHAMADO_SOLUCAO
							SET		STATUS = 'R'
							WHERE	IDCHAMADOSOLUCAO = ".$solucaoID;
							
			$sqlComentario = "	INSERT INTO HELP_CHAMADO_COMENTARIO (IDCHAMADOCOMENTARIO, IDCHAMADO, COMENTARIO, USUARIO, DTCOMENTARIO) VALUES
								(SEQ_HELP_CHAMADO_COMENTARIO.NEXTVAL, (SELECT IDCHAMADO FROM HELP_CHAMADO WHERE TOKEN = '".$chamadoToken."'), '".UTF8_DECODE($comentarioTexto)."', '".$comentarioNome."', TO_DATE('".$dataAtual."','DD/MM/YYYY HH24:MI:SS'))";
						
			IF($dba->query($sqlChamado)){
				IF($dba->query($sqlSolucao)){
					IF($dba->query($sqlComentario)){	
						$logDAO = NEW LogDAO();
						$logDAO->logInsert("Rejeitou a conclusão do chamado: ".$comentarioTexto, $chamadoToken);
						RETURN TRUE;	
					}
					ELSE{	RETURN FALSE;	}
				}ELSE{	RETURN FALSE;	}
			}ELSE{	RETURN FALSE;	}
		}
		
		//	***************************************************************
		//	Busca o último aviso ativo. É exibido na tela home de usuário/tec.
		PUBLIC FUNCTION avisoBusca(){
			$dba = $this->dba;
			
			$dataAtual = DATE("d/m/Y H:i:s");
			
			$sql = "SELECT  IDAVISO,
							TO_CHAR(DATAINI,'DD/MM/YYYY HH24:MI:SS') DATAINI,
							TO_CHAR(DATAFIM,'DD/MM/YYYY HH24:MI:SS') DATAFIM,
							AVISO,
							STATUS
					FROM    HELP_AVISO
					WHERE   DATAINI < TO_DATE('".$dataAtual."','DD/MM/YYYY HH24:MI:SS')
					AND     DATAFIM > TO_DATE('".$dataAtual."','DD/MM/YYYY HH24:MI:SS')
					AND		STATUS = 'A'";
					
			$stmt = $dba->query($sql);
			
			$row = OCI_FETCH_ARRAY($stmt, OCI_ASSOC);
			
			$aviso = NEW Chamados();
			
			$avisoID 		= $row['IDAVISO'];
			$avisoInicio 	= $row['DATAINI'];
			$avisoFim 		= $row['DATAFIM'];
			$avisoTexto 	= UTF8_ENCODE($row['AVISO']);
			$avisoStatus 	= $row['STATUS'];
			
			$aviso->setAvisoID($avisoID);
			$aviso->setAvisoInicio($avisoInicio);
			$aviso->setAvisoFim($avisoFim);
			$aviso->setAvisoTexto($avisoTexto);
			$aviso->setAvisoStatus($avisoStatus);
			
			RETURN $aviso;
		}
		
	
		
	}