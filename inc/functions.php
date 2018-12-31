<?php
/* For pagination. Count all the media per category */
function get_catalog_count($category = null,$search = null) {
    $category = strtolower($category);
    include("connection.php");

    try {
        $sql = "SELECT COUNT(media_id) FROM Media";
        if (!empty($search)) {
          $result = $db->prepare(
            $sql
            . " WHERE title LIKE ?"
          );
          $result->bindValue(1,'%'.$search.'%',PDO::PARAM_STR);
        } else if (!empty($category)) {
          $result = $db->prepare(
            $sql
            . " WHERE LOWER(category) = ?"
          );
          $result->bindParam(1,$category,PDO::PARAM_STR);
        } else {
          $result = $db->prepare($sql);
        }
        $result->execute();
    } catch (Exception $e) {
      echo "bad query";
    }
  
  $count = $result->fetchColumn(0);
  return $count;
}

/*Make an array with all the info in the databse */
function full_catalog_array($limit = null, $offset = 0) {
    include("connection.php");

    try {
        $sql = "SELECT media_id, title, category,img 
         FROM Media
         ORDER BY 
           REPLACE(
             REPLACE(
                REPLACE(title,'The ',''),
                'An ',
                ''
             ),
             'A ',
             ''
           )";
        if (is_integer($limit)) {
            $results = $db->prepare($sql . " LIMIT ? OFFSET ?");
            $results->bindParam(1,$limit,PDO::PARAM_INT);
            $results->bindParam(2,$offset,PDO::PARAM_INT);
        } else {
            $results = $db->prepare($sql);
        }
        $results->execute();
    } catch (Exception $e) {
        echo "Unable to retrieved results";
        exit;
    }

    $catalog = $results->fetchAll();
    return $catalog;
}

function search_catalog_array($search, $limit = null, $offset = 0) {
    include("connection.php");
    
    try {
       $sql = "SELECT media_id, title, category,img 
         FROM Media
         WHERE title LIKE ?
         ORDER BY 
         REPLACE(
           REPLACE(
              REPLACE(title,'The ',''),
              'An ',
              ''
           ),
           'A ',
           ''
         )";
       if (is_integer($limit)) {
          $results = $db->prepare($sql . " LIMIT ? OFFSET ?");
         $results->bindValue(1,"%".$search."%",PDO::PARAM_STR);
          $results->bindParam(2,$limit,PDO::PARAM_INT);
          $results->bindParam(3,$offset,PDO::PARAM_INT);
       } else {
         $results = $db->prepare($sql);
         $results->bindValue(1,"%".$search."%",PDO::PARAM_STR);
       }
       $results->execute();
    } catch (Exception $e) {
       echo "Unable to retrieved results";
       exit;
    }
    
    $catalog = $results->fetchAll();
    return $catalog;
}

/* query database for only the category int he url param */
function category_catalog_array($category, $limit = null, $offset = 0) {
    include("connection.php");
    $category = strtolower($category);
    try {
        $sql = "SELECT media_id, title, category,img 
         FROM Media
         WHERE LOWER(category) = ?
         ORDER BY 
         REPLACE(
           REPLACE(
              REPLACE(title,'The ',''),
              'An ',
              ''
           ),
           'A ',
           ''
         )";
        if (is_integer($limit)) {
            $results = $db->prepare($sql . " LIMIT ? OFFSET ?");
            $results->bindParam(1,$category,PDO::PARAM_STR);
            $results->bindParam(2,$limit,PDO::PARAM_INT);
            $results->bindParam(3,$offset,PDO::PARAM_INT);
        } else {
            $results = $db->prepare($sql);
            $results->bindParam(1,$category,PDO::PARAM_STR);
        }
        $results->execute();
    } catch (Exception $e) {
        echo "Unable to retrieved results";
        exit;
    }

    $catalog = $results->fetchAll();
    return $catalog;
}

/* make an array of 4 random database objects */
function random_catalog_array() {
    include("connection.php");

    try {
        $results = $db->query(
            "SELECT media_id, title, category,img 
         FROM Media
         ORDER BY RANDOM()
         LIMIT 4"
        );
    } catch (Exception $e) {
        echo "Unable to retrieved results";
        exit;
    }

    $catalog = $results->fetchAll();
    return $catalog;
}

/* Try to query the database and make the result into a POD statment object.  Joins the Media and Genres tables by the genre_id. Will also join Media and Books if book matches media. */
function single_item_array($id) {
    include("connection.php");

    try {
        $results = $db->prepare(
            "SELECT title, category, img, format, year, 
          publisher, isbn, genre
          FROM Media
          JOIN Genres ON Media.genre_id=Genres.genre_id
          LEFT OUTER JOIN Books 
          ON Media.media_id = Books.media_id
          WHERE Media.media_id = ?"
        );
        //Use Prepare and ? to protect against SQL injections. Binds the id varable to the ? palceholder
        $results->bindParam(1,$id,PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
        echo "bad query";
        echo $e;
    }
    //Calls the fetch method to retrieve item info for the 1 product that matches the id. loads the item variable. Will contain false if no item found
    $item = $results->fetch(PDO::FETCH_ASSOC);

    try {
        $result = $db->prepare("
              SELECT fullname,role
              FROM Media_People
              JOIN People ON Media_People.people_id=People.people_id
              WHERE media_id = ?");
        $result->bindParam(1,$id,PDO::PARAM_INT);
        $result->execute();
    } catch (Exception $e) {
        echo "bad query";
        echo $e;
    }
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $item[$row["role"]][] = $row["fullname"];
    }
    return $item;
}

/* set query param of item */
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