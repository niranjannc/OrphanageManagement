<?php

$server = "localhost";
$username = "root";
$password = "";
$dbname = "dborphanage";

function testData($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>