<?php
<?php
// headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Requête sur les produits
$stmt = $product->read();
$num = $stmt->rowCount();
 
// Vérifie si on a plus de 0 enregistrements trouvés
if($num>0){ 
    // tableau products
    $products_arr=array();
    $products_arr["records"]=array();
 
	/*
	On récupère le contenu de la table, fetch() est plus rapide que fetchAll(). 
	http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	*/
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){       
        extract($row);
		/*
		Extrait de la ligne, cela fera $row['name'] et sera seulement $name.
		*/
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
 
    // On définit le code réponse 200 à OK
    http_response_code(200);
 
    // On affiche les données des produits au format json
    echo json_encode($products_arr);
} else{
	// Code de réponse 404 - introuvable
	http_response_code(404);

	// Dire à l'utilisateur qu'aucun produit n'est produit.
	echo json_encode(
		array("message" => "Aucun produit trouvé.")
	);
}
 
// connection à la base de données ici

// On inclut les fichiers de connexion à la base de données
include_once '../config/database.php';
include_once '../objects/product.php';
 
// Instanciation de la base de donénes et de l'objet Product
$database = new Database();
$db = $database->getConnection();
 
// Intialisation de l'objet
$product = new Product($db);
 
// lire les produits se trouve ici
?>