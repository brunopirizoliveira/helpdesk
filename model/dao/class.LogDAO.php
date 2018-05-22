<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/model/inc/inc.config.php');
	
	CLASS LogDAO {
		PRIVATE $dba;
		
		PUBLIC FUNCTION LogDAO(){
			$dba = NEW DbAdmin('oracle');
			$dba->connect();
			$this->dba = $dba;
		}
		
		PUBLIC FUNCTION logInsert($logAcao, $logToken){
			$dba = $this->dba;
			
			$logUsername = $_SESSION['username'];
			$logData	= DATE("d/m/Y H:i:s");
			$logIP		= $_SERVER['REMOTE_ADDR'];
			
			$sql = "INSERT INTO HELP_LOG (IDLOG, DATALOG, USERNAME, ACAO, TOKEN, IP) VALUES
					(SEQ_HELP_LOG.NEXTVAL, TO_DATE('".$logData."','DD/MM/YYYY HH24:MI:SS'), UPPER('".$logUsername."'), '".utf8_decode($logAcao)."', '".$logToken."', '".$logIP."')";
					
			$dba->query($sql);
		}
	}