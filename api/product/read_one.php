<?php
// "headers" requis.
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// Inclure les fichiers de base de données et d'objets.
include_once '../config/database.php';
include_once '../objects/product.php';

// Obtenir la connexion à la base de données.
$database = new Database();
$db = $database->getConnection();

// Préparer l'objet "product".
$product = new Product($db);

// Définir la propriété "id" de l'enregistrement à lire.
$product->id = isset($_GET['id']) ? $_GET['id'] : die();

// Lire les détails du produit à éditer.
$product->readOne();

	if($product->name!=null){
		// Création d'un tableau.
		$product_arr = array(
			"id" =>  $product->id,
			"name" => $product->name,
			"description" => $product->description,
			"price" => $product->price,
			"category_id" => $product->category_id,
			"category_name" => $product->category_name

		);

		// Définir le code de réponse 200 OK
		http_response_code(200);

		// Rendre le format JSON.
		echo json_encode($product_arr);
	} else{
		// Code de réponse définit - 404 introuvable.
		http_response_code(404);

		// Dire à l'utilisateur que le produit n'existe pas.
		echo json_encode(array("message" => "Product does not exist."));
	}
?>