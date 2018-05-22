<?php

CLASS Log {
	
	PRIVATE $logID;
	PRIVATE $logData;
	PRIVATE $logUsername;
	PRIVATE $logAcao;
	PRIVATE $logIP;
	
	PUBLIC FUNCTION getLogID(){
		RETURN $this->logID;
	}
	PUBLIC FUNCTION getLogData(){
		RETURN $this->logData;
	}
	PUBLIC FUNCTION getLogUsername(){
		RETURN $this->logUsername;
	}
	PUBLIC FUNCTION getLogAcao(){
		RETURN $this->logAcao;
	}
	PUBLIC FUNCTION getLogToken(){
		RETURN $this->logToken;
	}
	PUBLIC FUNCTION getLogIP(){
		RETURN $this->logIP;
	}
	
	PUBLIC FUNCTION setLogID($logID){
		$this->logID = $logID;
	}
	PUBLIC FUNCTION setLogData($logData){
		$this->logData = $logData;
	}
	PUBLIC FUNCTION setLogUsername($logUsername){
		$this->logUsername = $logUsername;
	}
	PUBLIC FUNCTION setLogAcao($logAcao){
		$this->logAcao = $logAcao;
	}
	PUBLIC FUNCTION setLogToken($logToken){
		$this->logToken = $logToken;
	}
	PUBLIC FUNCTION setLogIP($logIP){
		$this->logIP = $logIP;
	}
}