<?php
// Spécifications des informations d'identification à la base de données
private $host = "localhost";
private $db_name = "api_db";
private $password = "";
private $conn;

// Obtenir la connexion à la base de données
public function getConnection(){
	$this->conn = null;
	
	try{
		$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
		$this->conn->exec("set names utf8");
	} catch(PDOException $exception){
		echo "Connection error: " . $exception->getMessage();
	}
	
	return $this->conn;
}
?>