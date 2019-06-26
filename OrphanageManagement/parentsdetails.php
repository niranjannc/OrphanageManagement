<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Parents Details under Orphanage" />
<meta name = "keywords" content = "Parents" />
<meta name = "viewport" content = "width=device-width, initial-scale=1.0" />

<link rel = "stylesheet" type = "text/css" href = "viewtable.css" />

<title>Parents Details</title>

</head>

<body>

<?php

include('connect.php');
$pid = testData($_POST["pid"]);
//$orgname = testData($_POST["orgname"]);

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	$sql1 = "SELECT * FROM parents WHERE pid = '$pid'";
	
	$conn->beginTransaction();
	$conn->exec($sql);
	$stmt = $conn->prepare($sql1);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	$val = $arr[0];
	$fname = ucwords($val["fname"]);
	$mname = ucwords($val["mname"]);
	$fbdate = $val["fbdate"];
	$mbdate = $val["mbdate"];
	$addlocation = $val["addlocation"];
	$addcity = $val["addcity"];
	$adddistrict = $val["adddistrict"];
	$addstate = $val["addstate"];
	$phone = $val["phone"];
	$agereq = $val["agereq"];
	$genreq = $val["genreq"];
	$photo = $val["photo"];

	//echo $fbdate;
	
	if($fname == "") {
		$fname = "---";
	}
	if($mname == "") {
		$mname = "---";
	}
	if($fbdate != "0000-00-00") {
		$fbdate = date("d-m-Y", strtotime($fbdate));
	}
	else {
		$fbdate = "---";
	}
	if($mbdate != "0000-00-00") {
		$mbdate = date("d-m-Y", strtotime($mbdate));
	}
	else {
		$mbdate = "---";
	}
	
	$agereq = ($agereq-1).' - '.($agereq+1);
	
	if( $genreq == "m" ) {
		$genreq = "Male";
	}
	else if($genreq == "f") {
		$genreq = "Female";
	}
	else if($genreq == "o") {
		$genreq = "Others";
	}
	
	if($photo == ""){
		//echo "HI";
		$photo = "default.png";
		$photo = file_get_contents($photo);
		$photo = base64_encode($photo);
	}
	
	$conn->commit();
	$conn=null;
}
catch(PDOException $e) {
	$conn->rollback();
	echo $e->getMessage();
}

?>
	<h1 align = "center">PARENTS DETAILS</h1>
	<div class = "viewcontents" style = "width : 40%;">
		<table>
			<tr>
				<th>Father Name</th>
				<td><?php echo $fname; ?></td>
			</tr>
			<tr>
				<th>Mother Name</th>
				<td><?php echo $mname; ?></td>
			</tr>
			<tr>
				<th>Father's DOB</th>
				<td><?php echo $fbdate; ?></td>
			</tr>
			<tr>
				<th>Mother's DOB</th>
				<td><?php echo $mbdate; ?></td>
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
				<th style = "height: 90px;">Address</th>
				<td><?php echo ucwords($addlocation.", ".$addcity.", ".$adddistrict.", ".$addstate);?></td>
			</tr>
			<tr>
				<th>Age Requirement</th>
				<td><?php echo $agereq; ?></td>
			</tr>
			<tr>
				<th>Gender Requirement</th>
				<td><?php echo $genreq; ?></td>
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