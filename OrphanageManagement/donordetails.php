<?php
session_start();
include('adminlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Donors Details under Trust" />
<meta name = "keywords" content = "Donors" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = "stylesheet" type = "text/css" href = "viewtable.css" />

<title>Donors Details</title>

</head>

<body>

<?php

include('connect.php');
$did = testData($_POST["did"]);
$orgname = testData($_POST["orgname"]);

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	$sql1 = "SELECT * FROM donor WHERE did = '$did'";
	
	$conn->beginTransaction();
	$conn->exec($sql);
	$stmt = $conn->prepare($sql1);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	$val = $arr[0];
	$fname = $val["fname"];
	$lname = $val["lname"];
	$gender = $val["gender"];
	$bdate = $val["bdate"];
	$addlocation = $val["addlocation"];
	$addcity = $val["addcity"];
	$adddistrict = $val["adddistrict"];
	$addstate = $val["addstate"];
	$phone = $val["phone"];
	$occupation = ucwords($val["occupation"]);
	$contribution = $val["contribution"];
	$role = ucwords($val["role"]);
	$photo = $val["photo"];

	$name = ucwords($fname." ".$lname);
	
	if( $gender == "m" ) {
		$gender = "Male";
	}
	else if($gender == "f") {
		$gender = "Female";
	}
	else if($gender == "o") {
		$gender = "Others";
	}
	
	if($photo == ""){
		//echo "HI";
		$photo = "default.png";
		$photo = file_get_contents($photo);
		$photo = base64_encode($photo);
	}
	
	$bdate = date("d-m-Y", strtotime($bdate));
	
	$conn->commit();
	$conn=null;
}
catch(PDOException $e) {
	$conn->rollback();
	echo $e->getMessage();
}

?>
	<h1 align = "center">DONORS DETAILS</h1>
	<div class = "viewcontents" style = "width : 40%">
		<table>
			<tr>
				<th>Orphanage</th>
				<td><?php echo $orgname; ?></td>
			</tr>
			<tr>
				<th>Name</th>
				<td><?php echo $name; ?></td>
			</tr>
			<tr>
				<th>Donor/Trustee</th>
				<td><?php echo $role; ?></td>
			</tr>
			<tr>
				<th>Gender</th>
				<td><?php echo $gender; ?></td>
			</tr>
			<tr>
				<th>Date of Birth</th>
				<td><?php echo $bdate; ?></td>
			</tr>
		</table>
	</div>
	<div class = "viewcontents" style = "width : 40%">
		<table>
			<tr>
				<th>Phone</th>
				<td><?php echo $phone; ?></td>
			</tr>
			<tr>
				<th style = "height: 100px;">Address</th>
				<td><?php echo ucwords($addlocation.", ".$addcity.", ".$adddistrict.", ".$addstate);?></td>
			</tr>
			<tr>
				<th>Occupation</th>
				<td><?php echo $occupation; ?></td>
			</tr>
			<tr>
				<th>Total Contribution</th>
				<td><?php echo $contribution; ?></td>
			</tr>
		</table>
	</div>
	<div class = "viewcontents" style = "width : 17%">
		<table>
			<tr>
				<img src = "data:image; base64, <?php echo $photo; ?>" width = "200px" height = "200px" />
			</tr>
		</table>
	</div>
	<?php include('footer.htm'); ?>
</body>
</html>