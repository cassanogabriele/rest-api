<?php
// "headers" requis 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// Inclusion des fichiers de base de données et des objets
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/product.php';
 
// Les services publics.
$utilities = new Utilities();
 
// Instanciation de la base de données et d'un objet "product"
$database = new Database();
$db = $database->getConnection();
 
// Initialisation de lo'bjet.
$product = new Product($db);
 
// Requête sur les produits
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();
 
// Vérifier si il y a plus de 0 enregistrements trouvés
if($num>0){ 
    // Tableau des produits
    $products_arr=array();
    $products_arr["records"]=array();
    $products_arr["paging"]=array();
 
    // Récupérer le contenu de la table
    // fetch() est plus rapide que fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // Extraction de la rangée.
        // cela fera $row['name'] pour seulement $name 
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
  
    // Inclusion de la pagination.
    $total_rows=$product->count();
    $page_url="{$home_url}product/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $products_arr["paging"]=$paging;
 
    // Définir le code de réponse - 200 OK
    http_response_code(200);
 
    // Rendre le format json 
    echo json_encode($products_arr);
}
 
else{ 
    // Définir le code de réponse - 404 Not found
    http_response_code(404);
 
    // Dire à l'utilisateur que les produits n'existent pas
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>