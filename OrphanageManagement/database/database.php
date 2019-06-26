<?php

include('../connect.php');

try {
	$conn = new PDO("mysql:host=$server",$username,$password);
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	
	echo "<h3>Connected Successfully</h3>";
	
	$sql = "CREATE DATABASE $dbname";
	$conn->exec($sql);
	
	echo "<h2>Database $dbname created Successfully</h2>";
}

catch (PDOException $e) {
	echo "<h2>$sql <br />".$e->getMessage()."</h2>";
}
?>