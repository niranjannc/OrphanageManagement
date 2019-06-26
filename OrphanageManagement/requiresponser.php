<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Infants who require under Orphanage" />
<meta name = "keywords" content = "Sponser List, List Infant's Sponser" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Sponsering required Infants</title>

</head>

<body>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" onclick = "window.location.href = 'infantslist.php'">Infants List</button></li>
<li><button type = "button" onclick = "window.location.href = 'infantform.php'">Add Infants</button></li>
<li><button type = "button" >Require Sponsering</button></li>
<li><button type = "button" onclick = "window.location.href = 'leftinfantslist.php'">Left Infants List</button></li>
</ul>
</div>

<h1>INFANTS WHO REQUIRE SPONSERING</h1>

<div class = "tabcontent">

<?php

include('connect.php');

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	
	$conn->beginTransaction();
	$conn->exec($sql);
	
	include('orgid.php');
	
	$sql = "SELECT infid, fname, lname, gender,
			truncate(((curdate()-bdate)/10000),0) as age
			FROM infant
			WHERE orgid = '$orgid' AND hasleft = 'n'
				AND infid NOT IN (SELECT infid FROM sponser)
			ORDER BY infid";
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	
	echo "<table>
			<tr>
				<th width='40%'>Name</th>
				<th width='20%'>Gender</th>
				<th width='20%'>Age</th>
				<th width='20%'>Add Sponserer</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"]).' '.ucwords($val["lname"]).'</td>
				<td class= "tabfont">';
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
				<td>
					<form method = "post" action = "addsponserer.php" >
						<input type = "hidden" name = "infid" value = "'.$val["infid"].'" />
						<input type = "hidden" name = "name" value = "'.ucwords($val["fname"]).' '.ucwords($val["lname"]).'" />
						<input type = "submit" name = "submit" value = "Add" class = "tabsubmit" />
					</form>
				</td>
			</tr>';
	}
	echo '</table>';
	if(sizeof($arr)==0) {
		echo '<h3 class="nodata" >Currently there is no infant without sponsering</h3>';
	}
	$conn->commit();
	$conn=null;
}
catch(PDOException $e) {
	$conn->rollback();
	echo $e->getMessage();
}

?>

</div>
<?php include('footer.htm'); ?>
</body>
</html>