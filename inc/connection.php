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


