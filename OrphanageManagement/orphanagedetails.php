<?php
session_start();
include('adminlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Orphanage Details under Trust" />
<meta name = "keywords" content = "Orphanages" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = "stylesheet" type = "text/css" href = "viewtable.css" />

<title>Orphanage Details</title>

</head>

<body>

<?php

include('connect.php');
$orgid = testData($_POST["view"]);

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	$sql1 = "SELECT * FROM orphanage WHERE orgid = '$orgid'";
	
	$conn->beginTransaction();
	$conn->exec($sql);
	$stmt = $conn->prepare($sql1);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	$val = $arr[0];
	$name = ucwords($val["name"]);
	$addlocation = ucwords($val["addlocation"]);
	$addcity = ucwords($val["addcity"]);
	$adddistrict = ucwords($val["adddistrict"]);
	$addstate = ucwords($val["addstate"]);
	$phone = $val["phone"];
	$email = $val["email"];
	$estyear = $val["estyear"];
	$trust = ucwords($val["trust"]);
	$head = $val["head"];
	
	$sql2 = "SELECT fname, lname FROM employee
				WHERE empid = '$head'";
				
	$stmt = $conn->prepare($sql2);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	$val = $arr[0];
	$head = ucwords($val["fname"]." ".$val["lname"]);
	
	$conn->commit();
	$conn=null;
}
catch(PDOException $e) {
	$conn->rollback();
	echo $e->getMessage();
}

?>
	<h1 align = "center">ORPHANAGE DETAILS</h1>
	<div class = "viewcontents" style = "width : 48%">
		<table>
			<tr>
				<th>Name</th>
				<td><?php echo $name; ?></td>
			</tr>
			<tr>
				<th>Established Year</th>
				<td><?php echo $estyear; ?></td>
			</tr>
			<tr>
				<th>Trust</th>
				<td><?php echo $trust; ?></td>
			</tr>
			<tr>
				<th>Head</th>
				<td><?php echo $head; ?></td>
			</tr>
		</table>
	</div>
	<div class = "viewcontents" style = "width : 48%">
		<table>
			<tr>
				<th>Phone</th>
				<td><?php echo $phone; ?></td>
			</tr>
			<tr>
				<th>Email</th>
				<td><?php echo $email; ?></td>
			</tr>
			<tr>
				<th style = "height: 150px;">Address</th>
				<td><?php echo $addlocation.", ".$addcity.", ".$adddistrict.", ".$addstate;?></td>
			</tr>
		</table>
	</div>
	<?php include('footer.htm'); ?>
</body>
</html>