<?php
// "headers" requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclure la base de donnée et le fichier objet
include_once '../config/database.php';
include_once '../objects/product.php';

// Obtenir la connexion à la base de données.
$database = new Database();
$db = $database->getConnection();

// Préparer l'objet du produit.
$product = new Product($db);

// Obtenir l'identifiant du produit.
$data = json_decode(file_get_contents("php://input"));

// Définir l'identifiant du produit à supprimer.
$product->id = $data->id;

// Supprimer le produit.
if($product->delete()){
	// Définir le code de réponse - 200 ok
	http_response_code(200);

	// Le dire à l'utilisateur.
	echo json_encode(array("message" => "Product was deleted."));
}

// Si impossible de supprimer le produit.
else{

	// Code de réponse définir - service 503 indisponible.
	http_response_code(503);

	// Le dire à l'utilisateur.
	echo json_encode(array("message" => "Unable to delete product."));
}
?>