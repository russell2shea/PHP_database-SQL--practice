<?php 
include("inc/functions.php");

$pageTitle = "Full Catalog";
$section = null;

if (isset($_GET["cat"])) {
    if ($_GET["cat"] == "books") {
        $pageTitle = "Books";
        $section = "books";
    } else if ($_GET["cat"] == "movies") {
        $pageTitle = "Movies";
        $section = "movies";
    } else if ($_GET["cat"] == "music") {
        $pageTitle = "Music";
        $section = "music";
    }
}

//if section is empty call the full array else call just the category array
if(empty($section)){
    $catalog = full_catalog_array();
} else{
    $catalog = category_catalog_array($section);
}



include("inc/header.php"); ?>

<div class="section catalog page">
    
    <div class="wrapper">
        
        <h1><?php 
        if ($section != null) {
            echo "<a href='catalog.php'>Full Catalog</a> &gt; ";
        }
        echo $pageTitle; ?></h1>
        
        <ul class="items">
            <?php
            foreach ($catalog as $item) {
                echo get_item_html($item);
            }
            ?>
        </ul>
        
    </div>
</div>

<?php include("inc/footer.php"); ?>