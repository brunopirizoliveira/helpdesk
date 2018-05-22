<?php

	define('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	require_once(__ROOT__.'/model/inc.autoload.php');
	require_once(__ROOT__.'/model/inc/inc.config.php');
	
	class UsuarioDAO {
		private $dba;
		
		public function UsuarioDAO(){
			$dba = new DbAdmin('oracle');
			$dba->connect();
			$this->dba = $dba;
		}
		
		// 	/////////////////////////////////////////////////////////
		//	Compara, no momento do login, se um usuário é técnico ou não.
		//	Se for tecnico, retorna sua área de atuação.
		//	Se for usuário, retorna 0.
		
		public function nivelAcesso($username){
			$dba = $this->dba;
			
			$sql = "SELECT	AREA
					FROM	HELP_CHAMADO_TECNICO
					WHERE	TECNICO = UPPER('$username')";
					
			$stmt = $dba->query($sql);
			
			while($row = oci_fetch_array($stmt, OCI_ASSOC)){
				$nivel = $row['AREA'];
			}
			
			if($nivel){	return $nivel;	}	// Apenas técnicos possuem nível de acesso.
			else{		return "0";		}	// No caso dos usuários, não existe registro no banco.
		}
	}