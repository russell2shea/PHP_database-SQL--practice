<?php 
include("inc/functions.php");

if (isset($_GET["id"])) {
    //Remove harmful material from the input
    $id = filter_input(INPUT_GET, "id",FILTER_SANITIZE_NUMBER_INT);
    single_item_array($id);
    $item = single_item_array($id);
}

if (empty($item)) {
    header("location:catalog.php");
    exit;
}

$pageTitle = $item["title"];
$section = null;

include("inc/header.php"); ?>

<div class="section page">

    <div class="wrapper">
        
        <div class="breadcrumbs">
            <a href="catalog.php">Full Catalog</a>
            &gt; <a href="catalog.php?cat=<?php echo strtolower($item["category"]); ?>">
            <?php echo $item["category"]; ?></a>
            &gt; <?php echo $item["title"]; ?>
        </div>
        
        <div class="media-picture">
    
        <span>
            <img src="<?php echo $item["img"]; ?>" alt="<?php echo $item["title"]; ?>" />
        </span>
            
        </div>
        
        <div class="media-details">
        
            <h1><?php echo $item["title"]; ?></h1>
            <table>
            
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>Category</th>
                    <td><?php echo $item["category"]; ?></td>
                </tr>
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>Genre</th>
                    <td><?php echo $item["genre"]; ?></td>
                </tr>
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>Format</th>
                    <td><?php echo $item["format"]; ?></td>
                </tr>
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>Year</th>
                    <td><?php echo $item["year"]; ?></td>
                </tr>
                <?php if (strtolower($item["category"]) == "books") { ?>
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>Authors</th>
                    <td><?php echo implode(", ",$item["author"]); ?></td>
                </tr>
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>Publisher</th>
                    <td><?php echo $item["publisher"]; ?></td>
                </tr>
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>ISBN</th>
                    <td><?php echo $item["isbn"]; ?></td>
                </tr>    
                <?php } else if (strtolower($item["category"]) == "movies") { ?>
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>Director</th>
                    <td><?php echo implode(", ",$item["director"]); ?></td>
                </tr>
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>Writers</th>
                    <td><?php echo implode(", ",$item["writer"]); ?></td>
                </tr>
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>Stars</th>
                    <td><?php echo implode(", ",$item["star"]); ?></td>
                </tr>
                <?php } else if (strtolower($item["category"]) == "music") { ?>
                <tr style="border-bottom: solid 2px #ccc;">
                    <th>Artist</th>
                    <td><?php echo implode(", ",$item["artist"]); ?></td>
                </tr>
                <?php } ?>
            </table>
        
        </div>
    
    </div>

</div>