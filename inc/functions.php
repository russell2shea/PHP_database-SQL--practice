<?php

function full_catalog_array(){
    include("connection.php");

    // Try to query the database and make the result into a POD statment object
    try {
        $results = $db->query("SELECT title, category, img FROM Media");
    } 

    // If can't query the database return an error message
    catch (Exception $e) {
        echo "Unable to retrieve results";
    }


    $catalog = $results->fetchAll();
    return $catalog;
}


function get_item_html($id,$item) {
    $output = "<li><a href='details.php?id="
        . $id . "'><img src='" 
        . $item["img"] . "' alt='" 
        . $item["title"] . "' />" 
        . "<p>View Details</p>"
        . "</a></li>";
    return $output;
}

function array_category($catalog,$category) {
    $output = array();
    
    foreach ($catalog as $id => $item) {
        if ($category == null OR strtolower($category) == strtolower($item["category"])) {
            $sort = $item["title"];
            $sort = ltrim($sort,"The ");
            $sort = ltrim($sort,"A ");
            $sort = ltrim($sort,"An ");
            $output[$id] = $sort;            
        }
    }
    
    asort($output);
    return array_keys($output);
}