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

<title>Sponsered Details</title>

</head>

<body>

<h1 align = "center">CURRENT SPONSERED DETAILS</h1>

<?php

include('connect.php');
$did = testData($_POST["did"]);
//$orgname = testData($_POST["orgname"]);

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	
	$conn->beginTransaction();
	include('orgid.php');
	
	$sql2 = "SELECT i.fname, i.lname, i.gender, e.fname as efname, e.lname as elname,
			truncate(((curdate()-i.bdate)/10000),0) as age
			FROM infant i, employee e, sponser s
			WHERE s.infid = i.infid AND i.caretakerid = e.empid
				AND s.did = '$did' AND i.orgid = '$orgid'
				AND i.hasleft = 'n'";
	
	$stmt = $conn->prepare($sql2);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	echo "<table align = 'center' style = 'width : 60%'>
			<tr>
				<th width='35%' >Infant</th>
				<th width='20%' >Infant's Gender</th>
				<th width='20%' >Infant's Age</th>
				<th width='30%'>Caretaker</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td class="tabfont">';
					$gender = $val["gender"];
					if( $gender == "m" ) {
						$gender = "Male";
					}
					else if($gender == "f") {
						$gender = "Female";
					}
					else if($gender == "o") {
						$gender = "Others";
					}
					echo $gender.'
				</td>
				<td class="tabfont">'.$val["age"].'</td>
				<td class="tabfont">'.ucwords($val["efname"])." ".ucwords($val["elname"]).'</td>
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
<?php include('footer.htm'); ?>
</body>
</html>