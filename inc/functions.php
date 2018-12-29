<?php

function full_catalog_array(){
    include("connection.php");

    // Try to query the database and make the result into a POD statment object
    try {
        $results = $db->query("SELECT media_id, title, category, img FROM Media");
    } 

    // If can't query the database return an error message
    catch (Exception $e) {
        echo "Unable to retrieve results";
    }


    $catalog = $results->fetchAll();
    return $catalog;
}

// Try to query the database and make the result into a POD statment object.  Joins the Media and Genres tables by the genre_id. Will also join Media and Books if book matches media. 
function single_item_array($id){
    include("connection.php");

    try {
        $results = $db->prepare(
            "SELECT title, category, img, format, year, genre, publisher, isbn
            FROM Media
            JOIN Genres ON Media.genre_id = Genres.genre_id
            LEFT OUTER JOIN Books ON Media.media_id = Books.media_id
            WHERE Media.media_id = ?"
        );
        //Use Prepare and ? to protect against SQL injections. Binds the id varable to the ? palceholder
        $results->bindParam(1,$id,PDO::PARAM_INT);
        //executes the query and loads the results into the results objects
        $results->execute();
    } 

    catch (Exception $e) {
        echo "Unable to retrieve results";
    }

    //Calls the fetch method to retrieve item info for the 1 product that matches the id. loads the item variable. Will contain false if no item found
    $item = $results->fetch();
    if (empty($item)) return $item;
        try {
            $results = $db->prepare(
                "SELECT fullname, role
                FROM Media_People
                JOIN People ON Media_People.people_id = People.people_id
                WHERE Media_People.media_id = ?"
            );
            //Use Prepare and ? to protect against SQL injections. Binds the id varable to the ? palceholder
            $results->bindParam(1,$id,PDO::PARAM_INT);
            //executes the query and loads the results into the results objects
            $results->execute();
        } 

        catch (Exception $e) {
            echo "Unable to retrieve results";
        }
        //loop through people and add them to the correct role in the item array
        while($row = $results->fetch(PDO::FETCH_ASSOC)){
            $item[$row["role"]][]= $row["fullname"];
        }
        return $item;
}

function random_catalog_array(){
    include("connection.php");

    // Try to query the database and make the result into a POD statment object
    try {
        $results = $db->query("
            SELECT media_id, title, category, img 
            FROM Media
            ORDER BY RANDOM()
            LIMIT 4"
        );
    } 

    // If can't query the database return an error message
    catch (Exception $e) {
        echo "Unable to retrieve results";
    }


    $catalog = $results->fetchAll();
    return $catalog;
}

function get_item_html($item) {
    $output = "<li><a href='details.php?id="
        . $item["media_id"] . "'><img src='" 
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