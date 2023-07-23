<?php
class Utilities{ 
    public function getPaging($page, $total_rows, $records_per_page, $page_url){ 
        // Tableau de pagination.
        $paging_arr=array();
 
        // Bouton pour la première page.
        $paging_arr["first"] = $page>1 ? "{$page_url}page=1" : "";
 
        // Compter tous les produits de la base de donnéees pour calculer le nombre total de pages.
        $total_pages = ceil($total_rows / $records_per_page);
 
        // Gamme de liens à afficher.
        $range = 2;
 
        // Afficher des liens vers "plage de pages" autour de la page actuelle
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
 
        $paging_arr['pages']=array();
        $page_count=0;
         
        for($x=$initial_num; $x<$condition_limit_num; $x++){
            // S'assurer que $x est supérieur à 0 et inférieur ou égal à $total_pages
            if(($x > 0) && ($x <= $total_pages)){
                $paging_arr['pages'][$page_count]["page"]=$x;
                $paging_arr['pages'][$page_count]["url"]="{$page_url}page={$x}";
                $paging_arr['pages'][$page_count]["current_page"] = $x==$page ? "yes" : "no";
 
                $page_count++;
            }
        }
 
        // Button pour la dernière page.
        $paging_arr["last"] = $page<$total_pages ? "{$page_url}page={$total_pages}" : "";
 
        // Format json.
        return $paging_arr;
    } 
}
?>