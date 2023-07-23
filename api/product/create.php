<?php
// "headers" requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// Obtenir la connexion à la base de données
include_once '../config/database.php';
 
// Instanciation de l'objet "product"
include_once '../objects/product.php';
 
$database = new Database();
$db = $database->getConnection();
 
$product = new Product($db);
 
// Obtenir les données envoyées
$data = json_decode(file_get_contents("php://input"));
 
// S'assurer que les données ne sont pas vides
if(
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->description) &&
    !empty($data->category_id)
){
 
    // Définir les valeurs des propriétés du produit
    $product->name = $data->name;
    $product->price = $data->price;
    $product->description = $data->description;
    $product->category_id = $data->category_id;
    $product->created = date('Y-m-d H:i:s');
 
    // Créer le produit.
    if($product->create()){ 
        // Définir le code de réponse défini en 201
        http_response_code(201);
 
        // Le dire à l'utilisateur 
        echo json_encode(array("message" => "Product was created."));
    }
 
    // Si on ne peut pas créer le produit, il faut le dire à l'utilisateur.
    else{
 
        // Code de réponse défini - service 503 indisponilbe
        http_response_code(503);
 
        // Le dire à l'utilisateur
        echo json_encode(array("message" => "Unable to create product."));
    }
}
 
// Dire que les données de l'utilisateur sont incomplètes
else{
 
    // Code de réponse défini en 400
    http_response_code(400);
 
    // Le dire à l'utilisateur
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}
?>