<?php
// "header" requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// Inclusion des fichiers de bases de données et d'objets.
include_once '../config/database.php';
include_once '../objects/category.php';
 
// Instancier l'objet de base de données et l'objet "category"
$database = new Database();
$db = $database->getConnection();
 
// Initialisation de l'objet.
$category = new Category($db);
 
// Requêtes sur les catégories
$stmt = $category->read();
$num = $stmt->rowCount();
 
// Vérifier si plus de 0 enregistrements sont trouvés.
if($num>0){ 
    // Tableau "products"
    $categories_arr=array();
    $categories_arr["records"]=array();
 
    // Récupérer le contenu de la table 
    // fetch() est plus rapide que fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // Extraire la rangée
        // cela fera $row['name'] à juste $name        
        extract($row);
 
        $category_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description)
        );
 
        array_push($categories_arr["records"], $category_item);
    }
 
    // Définir le code de réponse  - 200 OK
    http_response_code(200);
 
    // Afficher les données des catégories au format json
    echo json_encode($categories_arr);
}
 
else{
 
    // Définir le code de réponse - 404 Not found
    http_response_code(404);
 
    // Dire à l'utilisateur qu'aucune catégorie n'a été trouvée
    echo json_encode(
        array("message" => "No categories found.")
    );
}
?>