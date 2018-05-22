<?php

Class Usuario {
	
	private $id;
	private $usuario;
	private $empresa;
	private $login;
	private $password;
	private $nvlAcesso;


	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getUsuario(){
		return $this->usuario;
	}

	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	public function getEmpresa(){
		return $this->empresa;
	}

	public function setEmpresa($empresa){
		$this->empresa = $empresa;
	}

	public function getLogin(){
		return $this->login;
	}

	public function setLogin($login){
		$this->login = $login;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setPassword($password){
		$this->password = $password;
	}

	public function getNvlAcesso(){
		return $this->nvlAcesso;
	}

	public function setNvlAcesso($nvlAcesso){
		$this->nvlAcesso = $nvlAcesso;
	}

}