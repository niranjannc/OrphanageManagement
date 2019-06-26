<?php
$sql = "SELECT orgid 
			FROM employee, login
			WHERE id = empid
				AND empid = '$_SESSION[id]'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$arr = $stmt->fetchAll();
$val = $arr[0];
	
$orgid = $val["orgid"];
				
?>