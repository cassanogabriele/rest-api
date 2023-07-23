<?php
// "headers" requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// Inclure les fichiers de base de données et d'objets.
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/product.php';
 
// Instanciation de la base de données et d'un objet "product".
$database = new Database();
$db = $database->getConnection();
 
// Initialisation de l'objet.
$product = new Product($db);
 
// Obtenir les mots-clés.
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";
 
// Requête sur les produits.
$stmt = $product->search($keywords);
$num = $stmt->rowCount();
 
// On vérifie si il y a plus de 0 enregistrements trouvés.
if($num>0){ 
    // Tableau des produits.
    $products_arr=array();
    $products_arr["records"]=array();
 
    // Récupérer le contenu de la table.
    // fetch() est plus rapide que fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // Extraction de la rangée. Cela fera $row['name'] pour seulement $name       
        extract($row);
 
        $product_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );
 
        array_push($products_arr["records"], $product_item);
    }
 
    // Définir le code de réponse - 200 OK
    http_response_code(200);
 
    // Afficher les données sur les produits.
    echo json_encode($products_arr);
}
 
else{
    // Définir le code de réponse - 404 Not found
    http_response_code(404);
 
    // Dire à l'utilisateur qu'aucun produit n'a été trouvé.
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>