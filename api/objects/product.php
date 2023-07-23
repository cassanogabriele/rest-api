<?php
class Product{
	// Nom de la connection et nom de la table.
	private $conn;
	private $table_name = "product";
	
	// Propriétés de l'objet.
	public $id;
	public $name;
	public $description;
	public $price;
	public $category_id;
	public $category_name;
	public $created;
	
	// Constructeur avec $db comme connection à la base de données.
	public function _construct($db){
		$this->conn = $db;
	}
	
	// Lecture des produits.
	function read(){
		// Sélection de toutes les requêtes.
		$query = "SELECT
					c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
					FROM
					" . $this->table_name . " p
					LEFT JOIN
						categories c
							ON p.category_id = c.id
					ORDER BY
					p.created DESC";
					
		// Préparation de la requête. 
		$stmt = $this->conn->prepare($query);
		
		// Exécution de la requête.
		$stmt->execute();
		
		return $stmt;
	}
	
	// Création de produit
	function create(){
		// Requête pour insérer un enregistrement.
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					name=:name, price=:price, description=:description, category_id=:category_id, created=:created";

		// Préparation de la requête.
		$stmt = $this->conn->prepare($query);

		// Désinfecter.
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->price=htmlspecialchars(strip_tags($this->price));
		$this->description=htmlspecialchars(strip_tags($this->description));
		$this->category_id=htmlspecialchars(strip_tags($this->category_id));
		$this->created=htmlspecialchars(strip_tags($this->created));

		// Relier les valeurs.
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":price", $this->price);
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":category_id", $this->category_id);
		$stmt->bindParam(":created", $this->created);

		// Exécuter la requête.
		if($stmt->execute()){
			return true;
		}

		return false;
		
	}
	
	// Utilisé lors du remplissage du formulaire de mise à jour.
	function readOne(){
		// Requête pour lire un seul enregistrement 
		$query = "SELECT
					c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN
						categories c
							ON p.category_id = c.id
				WHERE
					p.id = ?
				LIMIT
					0,1";

		// Préparation de la requête.
		$stmt = $this->conn->prepare( $query );

		// Lier l'identifiant du produit à mettre à jour.
		$stmt->bindParam(1, $this->id);

		// Exécuter la requête.
		$stmt->execute();

		// Obtenir la ligne récupérée.
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// Définir des valeurs pour les propriétés d'objet.
		$this->name = $row['name'];
		$this->price = $row['price'];
		$this->description = $row['description'];
		$this->category_id = $row['category_id'];
		$this->category_name = $row['category_name'];
	}
	
	// Supprimer le produit.
	function delete(){	 
		// Requête de suppression.
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
	 
		// Préparation de la requête.
		$stmt = $this->conn->prepare($query);
	 
		// Désinfecter.
		$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// Lier l'identifiant de l'enregistrement à supprimer.
		$stmt->bindParam(1, $this->id);
	 
		// Exécuter la requête.
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
	
	// Lire les produits avec pagination
	public function readPaging($from_record_num, $records_per_page){	 
		// Requête "SELECT".
		$query = "SELECT
					c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN
						categories c
							ON p.category_id = c.id
				ORDER BY p.created DESC
				LIMIT ?, ?";
	 
		// Préparer une requête.
		$stmt = $this->conn->prepare( $query );
	 
		// Lier des valeurs variables.
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
	 
		// Exécuter la requête.
		$stmt->execute();
	 
		// Retourner les valeurs de la base de données.
		return $stmt;
	}
	
	// Utilisé pour les produits de "paging".
	public function count(){
		$query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
	 
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		return $row['total_rows'];
	}
}
?>