<?php

	CLASS Chamados{
	
	// Chamado
		PRIVATE	$chamadoID;
		PRIVATE $chamadoToken;
		PRIVATE	$chamadoEmpresa;
		PRIVATE $chamadoEmpresaID;
		PRIVATE $chamadoFilial;
		PRIVATE $chamadoFilialID;
		PRIVATE $chamadoNome;
		PRIVATE $chamadoIP;
		PRIVATE $chamadoSetor;
		PRIVATE $chamadoSetorID;
		PRIVATE $chamadoTipo;
		PRIVATE $chamadoTipoID;
		PRIVATE $chamadoArea;
		PRIVATE $chamadoAreaID;
		PRIVATE $chamadoCategoria;
		PRIVATE $chamadoCategoriaID;
		PRIVATE $chamadoPrioridade;
		PRIVATE $chamadoPrioridadeID;
		PRIVATE $chamadoTitulo;
		PRIVATE $chamadoDescricao;
		PRIVATE $chamadoAbertura;
		PRIVATE $chamadoConclusao;
		PRIVATE $chamadoStatus;
		PRIVATE $chamadoStatusID;
		PRIVATE $chamadoUsername;
		
	// Chamado_Acompanhamento
		PRIVATE $acompanhamentoID;
		PRIVATE $acompanhamentoTecnico;
		PRIVATE $acompanhamentoData;
		
	// Chamado_Anexo
		PRIVATE $anexoID;
		PRIVATE $anexoArquivo;
		PRIVATE $anexoDiretorio;
		PRIVATE $anexoNome;
		PRIVATE $anexoData;
	
	// Chamado_Comentario
		PRIVATE $comentarioID;
		PRIVATE $comentarioTexto;
		PRIVATE $comentarioNome;
		PRIVATE $comentarioData;
		PRIVATE $comentarioTipo;
		
	// Chamado_Solucao
		PRIVATE $solucaoID;
		PRIVATE $solucaoNome;
		PRIVATE $solucaoData;
		PRIVATE $solucaoTexto;
		PRIVATE $solucaoRank;
		
	// Categoria
		PRIVATE	$categoriaID;
		PRIVATE $categoriaNome;
		PRIVATE $categoriaArea;
		
	//	Avisos
		PRIVATE $avisoID;
		PRIVATE $avisoInicio;
		PRIVATE $avisoFim;
		PRIVATE $avisoTexto;
		PRIVATE $avisoStatus;
		
		
	// Métodos GET Chamado
		PUBLIC FUNCTION getChamadoID(){
			RETURN $this->chamadoID;
		}
		PUBLIC FUNCTION getChamadoToken(){
			RETURN $this->chamadoToken;
		}
		PUBLIC FUNCTION getChamadoEmpresa(){
			RETURN $this->chamadoEmpresa;
		}
		PUBLIC FUNCTION getChamadoEmpresaID(){
			RETURN $this->chamadoEmpresaID;
		}
		PUBLIC FUNCTION getChamadoFilial(){
			RETURN $this->chamadoFilial;
		}
		PUBLIC FUNCTION getChamadoFilialID(){
			RETURN $this->chamadoFilialID;
		}
		PUBLIC FUNCTION getChamadoNome(){
			RETURN $this->chamadoNome;
		}
		PUBLIC FUNCTION getChamadoIP(){
			RETURN $this->chamadoIP;
		}
		PUBLIC FUNCTION getChamadoSetor(){
			RETURN $this->chamadoSetor;
		}
		PUBLIC FUNCTION getChamadoSetorID(){
			RETURN $this->chamadoSetorID;
		}
		PUBLIC FUNCTION getChamadoTipo(){
			RETURN $this->chamadoTipo;
		}
		PUBLIC FUNCTION getChamadoTipoID(){
			RETURN $this->chamadoTipoID;
		}
		PUBLIC FUNCTION getChamadoArea(){
			RETURN $this->chamadoArea;
		}
		PUBLIC FUNCTION getChamadoAreaID(){
			RETURN $this->chamadoAreaID;
		}
		PUBLIC FUNCTION getChamadoCategoria(){
			RETURN $this->chamadoCategoria;
		}
		PUBLIC FUNCTION getChamadoCategoriaID(){
			RETURN $this->chamadoCategoriaID;
		}
		PUBLIC FUNCTION getChamadoPrioridade(){
			RETURN $this->chamadoPrioridade;
		}
		PUBLIC FUNCTION getChamadoPrioridadeID(){
			RETURN $this->chamadoPrioridadeID;
		}
		PUBLIC FUNCTION getChamadoTitulo(){
			RETURN $this->chamadoTitulo;
		}
		PUBLIC FUNCTION getChamadoDescricao(){
			RETURN $this->chamadoDescricao;
		}
		PUBLIC FUNCTION getChamadoAbertura(){
			RETURN $this->chamadoAbertura;
		}
		PUBLIC FUNCTION getChamadoConclusao(){
			RETURN $this->chamadoConclusao;
		}
		PUBLIC FUNCTION getChamadoStatus(){
			RETURN $this->chamadoStatus;
		}
		PUBLIC FUNCTION getChamadoStatusID(){
			RETURN $this->chamadoStatusID;
		}
		PUBLIC FUNCTION getChamadoUsername(){
			RETURN $this->chamadoUsername;
		}
		
	// Métodos SET Chamado
		PUBLIC FUNCTION setChamadoID($chamadoID){
			$this->chamadoID = $chamadoID;
		}
		PUBLIC FUNCTION setChamadoToken($chamadoToken){
			$this->chamadoToken = $chamadoToken;
		}
		PUBLIC FUNCTION setChamadoEmpresa($chamadoEmpresa){
			$this->chamadoEmpresa = $chamadoEmpresa;
		}
		PUBLIC FUNCTION setChamadoEmpresaID($chamadoEmpresaID){
			$this->chamadoEmpresaID = $chamadoEmpresaID;
		}
		PUBLIC FUNCTION setChamadoFilial($chamadoFilial){
			$this->chamadoFilial = $chamadoFilial;
		}
		PUBLIC FUNCTION setChamadoFilialID($chamadoFilialID){
			$this->chamadoFilialID = $chamadoFilialID;
		}
		PUBLIC FUNCTION setChamadoNome($chamadoNome){
			$this->chamadoNome = $chamadoNome;
		}
		PUBLIC FUNCTION setChamadoIP($chamadoIP){
			$this->chamadoIP = $chamadoIP;
		}
		PUBLIC FUNCTION setChamadoSetor($chamadoSetor){
			$this->chamadoSetor = $chamadoSetor;
		}
		PUBLIC FUNCTION setChamadoSetorID($chamadoSetorID){
			$this->chamadoSetorID = $chamadoSetorID;
		}
		PUBLIC FUNCTION setChamadoTipo($chamadoTipo){
			$this->chamadoTipo = $chamadoTipo;
		}
		PUBLIC FUNCTION setChamadoTipoID($chamadoTipoID){
			$this->chamadoTipoID = $chamadoTipoID;
		}
		PUBLIC FUNCTION setChamadoArea($chamadoArea){
			$this->chamadoArea = $chamadoArea;
		}
		PUBLIC FUNCTION setChamadoAreaID($chamadoAreaID){
			$this->chamadoAreaID = $chamadoAreaID;
		}
		PUBLIC FUNCTION setChamadoCategoria($chamadoCategoria){
			$this->chamadoCategoria = $chamadoCategoria;
		}
		PUBLIC FUNCTION setChamadoCategoriaID($chamadoCategoriaID){
			$this->chamadoCategoriaID = $chamadoCategoriaID;
		}
		PUBLIC FUNCTION setChamadoPrioridade($chamadoPrioridade){
			$this->chamadoPrioridade = $chamadoPrioridade;
		}
		PUBLIC FUNCTION setChamadoPrioridadeID($chamadoPrioridadeID){
			$this->chamadoPrioridadeID = $chamadoPrioridadeID;
		}
		PUBLIC FUNCTION setChamadoTitulo($chamadoTitulo){
			$this->chamadoTitulo = $chamadoTitulo;
		}
		PUBLIC FUNCTION setChamadoDescricao($chamadoDescricao){
			$this->chamadoDescricao = $chamadoDescricao;
		}
		PUBLIC FUNCTION setChamadoAbertura($chamadoAbertura){
			$this->chamadoAbertura = $chamadoAbertura;
		}
		PUBLIC FUNCTION setChamadoConclusao($chamadoConclusao){
			$this->chamadoConclusao = $chamadoConclusao;
		}
		PUBLIC FUNCTION setChamadoStatus($chamadoStatus){
			$this->chamadoStatus = $chamadoStatus;
		}
		PUBLIC FUNCTION setChamadoStatusID($chamadoStatusID){
			$this->chamadoStatusID = $chamadoStatusID;
		}
		PUBLIC FUNCTION setChamadoUsername($chamadoUsername){
			$this->chamadoUsername = $chamadoUsername;
		}
		
	// Métodos GET Chamado_Acompanhamento
		PUBLIC FUNCTION getAcompanhamentoID(){
			RETURN $this->acompanhamentoID;
		}
		PUBLIC FUNCTION getAcompanhamentoTecnico(){
			RETURN $this->acompanhamentoTecnico;
		}
		PUBLIC FUNCTION getAcompanhamentoData(){
			RETURN $this->acompanhamentoData;
		}
	
	// Métodos SET Chamado_Acompanhamento
		PUBLIC FUNCTION setAcompanhamentoID($acompanhamentoID){
			$this->acompanhamentoID = $acompanhamentoID;
		}
		PUBLIC FUNCTION setAcompanhamentoTecnico($acompanhamentoTecnico){
			$this->acompanhamentoTecnico = $acompanhamentoTecnico;
		}
		PUBLIC FUNCTION setAcompanhamentoData($acompanhamentoData){
			$this->acompanhamentoData = $acompanhamentoData;
		}
		
	// Métodos GET Chamado_Anexo
		PUBLIC FUNCTION getAnexoID(){
			RETURN $this->anexoID;
		}
		PUBLIC FUNCTION getAnexoArquivo(){
			RETURN $this->anexoArquivo;
		}
		PUBLIC FUNCTION getAnexoDiretorio(){
			RETURN $this->anexoDiretorio;
		}
		PUBLIC FUNCTION getAnexoNome(){
			RETURN $this->anexoNome;
		}
		PUBLIC FUNCTION getAnexoData(){
			RETURN $this->anexoData;
		}
		
	// Métodos SET Chamado_Anexo
		PUBLIC FUNCTION setAnexoID($anexoID){
			$this->anexoID = $anexoID;
		}
		PUBLIC FUNCTION setAnexoArquivo($anexoArquivo){
			$this->anexoArquivo = $anexoArquivo;
		}
		PUBLIC FUNCTION setAnexoDiretorio($anexoDiretorio){
			$this->anexoDiretorio = $anexoDiretorio;
		}
		PUBLIC FUNCTION setAnexoNome($anexoNome){
			$this->anexoNome = $anexoNome;
		}
		PUBLIC FUNCTION setAnexoData($anexoData){
			$this->anexoData = $anexoData;
		}
		
	// Métodos GET Chamado_Comentario
		PUBLIC FUNCTION getComentarioID(){
			RETURN $this->comentarioID;
		}
		PUBLIC FUNCTION getComentarioTexto(){
			RETURN $this->comentarioTexto;
		}
		PUBLIC FUNCTION getComentarioNome(){
			RETURN $this->comentarioNome;
		}
		PUBLIC FUNCTION getComentarioData(){
			RETURN $this->comentarioData;
		}
		PUBLIC FUNCTION getComentarioTipo(){
			RETURN $this->comentarioTipo;
		}
	
	// Métodos SET Chamado_Comentario
		PUBLIC FUNCTION setComentarioID($comentarioID){
			$this->comentarioID = $comentarioID;
		}
		PUBLIC FUNCTION setComentarioTexto($comentarioTexto){
			$this->comentarioTexto = $comentarioTexto;
		}
		PUBLIC FUNCTION setComentarioNome($comentarioNome){
			$this->comentarioNome = $comentarioNome;
		}
		PUBLIC FUNCTION setComentarioData($comentarioData){
			$this->comentarioData = $comentarioData;
		}
		PUBLIC FUNCTION setComentarioTipo($comentarioTipo){
			$this->comentarioTipo = $comentarioTipo;
		}
		
	// Métodos GET Chamado_Solucao
		PUBLIC FUNCTION getSolucaoID(){
			RETURN $this->solucaoID;
		}
		PUBLIC FUNCTION getSolucaoNome(){
			RETURN $this->solucaoNome;
		}
		PUBLIC FUNCTION getSolucaoData(){
			RETURN $this->solucaoData;
		}
		PUBLIC FUNCTION getSolucaoTexto(){
			RETURN $this->solucaoTexto;
		}
		PUBLIC FUNCTION getSolucaoRank(){
			RETURN $this->solucaoRank;
		}
		
	// Métodos SET Chamado_Solucao
		PUBLIC FUNCTION setSolucaoID($solucaoID){
			$this->solucaoID = $solucaoID;
		}
		PUBLIC FUNCTION setSolucaoNome($solucaoNome){
			$this->solucaoNome = $solucaoNome;
		}
		PUBLIC FUNCTION setSolucaoData($solucaoData){
			$this->solucaoData = $solucaoData;
		}
		PUBLIC FUNCTION setSolucaoTexto($solucaoTexto){
			$this->solucaoTexto = $solucaoTexto;
		}
		PUBLIC FUNCTION setSolucaoRank($solucaoRank){
			$this->solucaoRank = $solucaoRank;
		}
		
	// Métodos GET Categoria
		PUBLIC FUNCTION getCategoriaID(){
			RETURN $this->categoriaID;
		}
		PUBLIC FUNCTION getCategoriaNome(){
			RETURN $this->categoriaNome;
		}
		PUBLIC FUNCTION getCategoriaArea(){
			RETURN $this->categoriaArea;
		}
		
	// Métodos SET Categoria
		PUBLIC FUNCTION setCategoriaID($categoriaID){
			$this->categoriaID = $categoriaID;
		}
		PUBLIC FUNCTION setCategoriaNome($categoriaNome){
			$this->categoriaNome = $categoriaNome;
		}
		PUBLIC FUNCTION setCategoriaArea($categoriaArea){
			$this->categoriaArea = $categoriaArea;
		}
		
	// Métodos GET Aviso
		PUBLIC FUNCTION getAvisoID(){
			RETURN $this->avisoID;
		}
		PUBLIC FUNCTION getAvisoInicio(){
			RETURN $this->avisoInicio;
		}
		PUBLIC FUNCTION getAvisoFim(){
			RETURN $this->avisoFim;
		}
		PUBLIC FUNCTION getAvisoTexto(){
			RETURN $this->avisoTexto;
		}
		PUBLIC FUNCTION getAvisoStatus(){
			RETURN $this->avisoStatus;
		}
		
	// Métodos SET Aviso
		PUBLIC FUNCTION setAvisoID($avisoID){
			$this->avisoID = $avisoID;
		}
		PUBLIC FUNCTION setAvisoInicio($avisoInicio){
			$this->avisoInicio = $avisoInicio;
		}
		PUBLIC FUNCTION setAvisoFim($avisoFim){
			$this->avisoFim = $avisoFim;
		}
		PUBLIC FUNCTION setAvisoTexto($avisoTexto){
			$this->avisoTexto = $avisoTexto;
		}
		PUBLIC FUNCTION setAvisoStatus($avisoStatus){
			$this->avisoStatus = $avisoStatus;
		}
	
	}