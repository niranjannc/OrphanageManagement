<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Sponsered Details of a Donor" />
<meta name = "keywords" content = "Sponser" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Sponserer List</title>

</head>

<body>

<h1 align = "center">SPONSERER DETAILS</h1>

<?php

include('connect.php');
$infid = testData($_POST["infid"]);
//$orgname = testData($_POST["orgname"]);

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	
	$conn->beginTransaction();
	include('orgid.php');
	
	$sql2 = "SELECT d.fname, d.lname, d.phone
			FROM infant i, donor d, sponser s
			WHERE s.infid = i.infid AND s.did = d.did
				AND i.infid = '$infid' AND i.orgid = '$orgid'";
	
	$stmt = $conn->prepare($sql2);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	echo "<table align = 'center' style = 'width : 60%'>
			<tr>
				<th width='35%' >Donor</th>
				<th width='35%' >Contact</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td class="tabfont">'.$val["phone"].'</td>
			</tr>';
	}
	echo '</table>';
	
	$conn->commit();
	$conn=null;
}
catch(PDOException $e) {
	$conn->rollback();
	echo $e->getMessage();
}

?>
</body>
<?php include('footer.htm'); ?>
</html>