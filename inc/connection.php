<?php
// Try to connect to the database and if successful make that an object
try {
	$db = new PDO("sqlite:".__DIR__."/database.db");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 

// If can't connect to the database a message will be displayed and the program will end
catch (Exception $e){
	echo "Unable to Connect";
	echo $e->getMessage();
	exit;
}

// Try to query the database and make the result into a POD statment object
try {
	$results = $db->query("SELECT title, category, img FROM Media");
} 

// If can't query the database return an error message
catch (Exception $e) {
	echo "Unable to retrieve results";
}


$catalog = $results->fetchAll();


//if you have a specific instance of a class you use the single arrow and if you call a method on a property its self you use the double colon