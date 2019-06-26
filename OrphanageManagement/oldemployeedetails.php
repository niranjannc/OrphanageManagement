<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Old Employees Details under Orphanage" />
<meta name = "keywords" content = "Old Employee" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = "stylesheet" type = "text/css" href = "viewtable.css" />

<title>Old Employees Details</title>

</head>

<body>

<?php

include('connect.php');
$empid = testData($_POST["empid"]);
//$orgname = testData($_POST["orgname"]);

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	$sql1 = "SELECT * FROM employee WHERE empid = '$empid'";
	
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
	$designation = ucwords($val["designation"]);
	$salary = $val["salary"];
	$joindate = $val["joindate"];
	$leavedate = $val["leavedate"];
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
	if($bdate != "") 
		$bdate = date("d-m-Y", strtotime($bdate));
	else
		$bdate = "---";
	
	$joindate = date("d-m-Y", strtotime($joindate));
	$leavedate = date("d-m-Y", strtotime($leavedate));
	
	$conn->commit();
	$conn=null;
}
catch(PDOException $e) {
	$conn->rollback();
	echo $e->getMessage();
}

?>
	<h1 align = "center">OLD EMPLOYEES DETAILS</h1>
	<div class = "viewcontents" style = "width : 40%;">
		<table style="height:450px;">
			<tr>
				<th>Name</th>
				<td><?php echo $name; ?></td>
			</tr>
			<tr>
				<th>Gender</th>
				<td><?php echo $gender; ?></td>
			</tr>
			<tr>
				<th>Date of Birth</th>
				<td><?php echo $bdate; ?></td>
			</tr>
			<tr>
				<th>Join Date</th>
				<td><?php echo $joindate; ?></td>
			</tr>
			<tr>
				<th>Leave Date</th>
				<td><?php echo $leavedate; ?></td>
			</tr>
		</table>
	</div>
	<div class = "viewcontents" style = "width : 40%">
		<table style="height:450px">
			<tr>
				<th>Phone</th>
				<td><?php echo $phone; ?></td>
			</tr>
			<tr>
				<th style = "height: 90px;">Address</th>
				<td><?php echo ucwords($addlocation.", ".$addcity.", ".$adddistrict.", ".$addstate);?></td>
			</tr>
			<tr>
				<th>Designation when leaving</th>
				<td><?php echo $designation; ?></td>
			</tr>
			<tr>
				<th>Salary when leaving</th>
				<td><?php echo $salary; ?></td>
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