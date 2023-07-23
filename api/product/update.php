<?php
// "headers" requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclure les fichiers de base de données et d'objets.
include_once '../config/database.php';
include_once '../objects/product.php';

// Obtenir la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Préparer l'objet "product"
$product = new Product($db);

// Obtenir l'identifiant du produit à éditer.
$data = json_decode(file_get_contents("php://input"));

// Définir la propriété "id" du produit à modifier.
$product->id = $data->id;

// Définir les valeurs des propriétés de produit.
$product->name = $data->name;
$product->price = $data->price;
$product->description = $data->description;
$product->category_id = $data->category_id;

// Mettre à jour le produit.
if($product->update()){
	// Définir le code de réponse - 200 OK0
	http_response_code(200);

	// Le dire à l'utilisateur.
	echo json_encode(array("message" => "Product was updated."));
} else{
	// Sinon, si il est impossible de mettre à jour le produit, informer l'utilisateur 
	
	// Code de réponse défini - service 503 indisponible.
	http_response_code(503);

	// Le dire à l'utilisateur.
	echo json_encode(array("message" => "Unable to update product."));
}
?>
