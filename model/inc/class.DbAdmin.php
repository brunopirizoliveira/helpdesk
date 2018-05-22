<?php


class DbAdmin{

	//propriedades
	private $tipo;
	private $conn;


	//metodo construtor
	public function DbAdmin($tipo){

		$this->tipo = $tipo;
	}

	//metodo que conecta com o SGBD

	public function connect(){

		switch($this->tipo)	{

			case 'mysql':

				$this->conn = mysql_connect('localhost', 'root', '');
				mysql_select_db('conexao');

			break;

			case 'pgsql':

				$string = 'host='.$host.' port=5432 dbname ='.$base.' user='.$user.' password='.$pass;
				$this->conn = pg_connect($string);

			break;


			case 'oracle':

				$this->conn = ocilogon($user, $pass, $host);

			break;

		}// fim switch($this->tipo)

	}// fim public function connect ($host, $user, $pass, $base)


	//m��todo que executa uma instru����o SQL e retorna um resultado
	public function query($sql){

		switch($this->tipo){

			case 'mysql':

				$res = mysql_query($sql, $this->conn) or die (mysql_error());

			break;

			case 'pgsql':

				$res = pg_query($this->conn, $sql) or die ('bug');

			break;

			case 'oracle':

				$res = oci_parse($this->conn,$sql)  or die('ERRO NA ANÁLISE DA CLÁUSULA SQL');
				oci_execute($res) or die('ERRO NA EXECUÇÃO');

			break;

		}// fim switch($this->tipo){

		return $res;

	}// fim public function query($sql)


	//m��todo que fecha a conex��o com o SGBD
	public function close($res){

		switch($this->tipo){

			case 'mysql':

				mysql_close($this->conn);

			break;

			case 'pgsql':

				pg_close($this->conn);

			break;

			case 'oracle':

				ocilogoff($conn);

			break;

		}// fim switch($this->tipo){

	}// fim public function lastid($res)

}
