<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Donors Details under Orphanage" />
<meta name = "keywords" content = "Donors" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = "stylesheet" type = "text/css" href = "viewtable.css" />

<title>Left Infants Details</title>

</head>

<body>

<?php

include('connect.php');
$infid = testData($_POST["infid"]);
//$orgname = testData($_POST["orgname"]);

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	$sql1 = "SELECT * FROM infant WHERE infid = '$infid'";
	
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
	$disabilility = $val["disability"];
	$caretaker = $val["caretakerid"];
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
	
	$bdate = date("d-m-Y", strtotime($bdate));
	$joindate = date("d-m-Y", strtotime($joindate));
	$leavedate = date("d-m-Y", strtotime($leavedate));
	
	if($disabilility == "PH") {
		$disabilility = "Physically Hnadicapped";
	}
	else if($disabilility == "MD") {
		$disabilility = "Mentally Disabled";
	}
	else if($disabilility == "Both") {
		$disabilility = "Both Physically and Mentally Disabled";
	}
	
	$sql = "SELECT fname, lname
			FROM employee
			WHERE empid='$caretaker'";
			
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	$caretaker="";
	foreach($arr as $val) {
		$caretaker = ucwords($val["fname"]." ".$val["lname"]);
	}
	if($caretaker=="") {
		$caretaker="---";
	}
	
	$sql = "SELECT d.fname, d.lname
			FROM sponser s, donor d
			WHERE s.did=d.did
				AND s.infid='$infid'";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	$sponser="";
	foreach($arr as $val){
		$sponser .= ucwords($val["fname"]." ".$val["lname"])."<br />";
	}
	
	$conn->commit();
	$conn=null;
}
catch(PDOException $e) {
	$conn->rollback();
	echo $e->getMessage();
}

?>
	<h1 align = "center">INFANT DETAILS</h1>
	<div class = "viewcontents" style = "width : 40%;">
		<table style="height:500px;">
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
				<th>Disability</th>
				<td><?php echo $disabilility; ?></td>
			</tr>
		</table>
	</div>
	<div class = "viewcontents" style = "width : 40%">
		<table style="height:500px;">
			<tr>
				<th>Join Date</th>
				<td><?php echo $joindate; ?></td>
			</tr>
			<tr>
				<th >Leave Date</th>
				<td><?php echo $leavedate;?></td>
			</tr>
			<tr>
				<th>Caretaker while Leaving</th>
				<td><?php echo $caretaker;?></td>
			</tr>
			<tr>
				<th>Sponseres while Leaving</th>
				<td>
					<?php
						echo $sponser;
					?>
				</td>
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