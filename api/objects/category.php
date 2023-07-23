<?php
class Category{
    // Connexion à la base de données et nom de la table.
    private $conn;
    private $table_name = "categories";
 
    // Propriétés des objets.
    public $id;
    public $name;
    public $description;
    public $created;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // Utilisé pour sélectionner la liste déroulante
    public function readAll(){
        // Selection de toutes les données.
        $query = "SELECT
                    id, name, description
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }
	
	// Utilisé pour sélectionner la liste déroulante.
	public function read(){	 
		// Sélectionner toutes les données.
		$query = "SELECT
					id, name, description
				FROM
					" . $this->table_name . "
				ORDER BY
					name";
	 
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
	 
		return $stmt;
	}
}
?>