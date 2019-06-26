<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Infants under Orphanage" />
<meta name = "keywords" content = "Infants, List Infants" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Infants</title>

</head>

<body>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" >Infants List</button></li>
<li><button type = "button" onclick = "window.location.href = 'infantform.php'">Add Infants</button></li>
<li><button type = "button" onclick = "window.location.href = 'requiresponser.php'">Require Sponsering</button></li>
<li><button type = "button" onclick = "window.location.href = 'leftinfantslist.php'">Left Infants List</button></li>
</ul>
</div>

<h1>INFANTS LIST</h1>

<div class = "tabcontent">

<?php

include('connect.php');

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	/*$sql1 = "SELECT orgid 
			FROM employee, login
			WHERE id = empid
				AND empid = '$_SESSION[id]'"; */
	
	$conn->beginTransaction();
	$conn->exec($sql);
	/*$stmt = $conn->prepare($sql1);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	$val = $arr[0];
	
	$orgid = $val["orgid"];*/
	
	include('orgid.php');
	
	$sql2 = "SELECT infid, fname, lname
			FROM infant
			WHERE orgid = '$orgid' AND hasleft = 'n'
			ORDER BY infid";
	
	$stmt = $conn->prepare($sql2);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	
	echo "<table style='width:100%'>
			<tr>
				<th width='40%'>Name</th>
				<th width='15%'>Details</th>
				<th width='15%'>Edit</th>
				<th width='15%'>Add Sponserer</th>
				<th width='15%'>View Sponserers</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td>
					<form method = "POST" action = "infantdetails.php" target = "_blank">
						<input type="hidden" name="infid" value="'.$val["infid"].'"/>
						<input type="submit" value="View" class = "tabsubmit" />
					</form>
				</td>
				<td>
					<form method = "POST" action = "infantedit.php">
						<input type="hidden" name="infid" value="'.$val["infid"].'"/>
						<input type="hidden" name="name" value="'.$val["fname"].' '.$val["lname"].'"/>
						<input type="submit" value="Edit" class = "tabsubmit" />
					</form>
				</td>
				<td>
					<form method = "post" action = "addsponserer.php" >
						<input type = "hidden" name = "infid" value = "'.$val["infid"].'" />
						<input type = "hidden" name = "name" value = "'.ucwords($val["fname"]).' '.ucwords($val["lname"]).'" />
						<input type = "submit" name = "submit" value = "Add" class = "tabsubmit" />
					</form>
				</td>
				<td>
					<form method = "POST" action = "sponsererslist.php" target = "_blank">
						<input type="hidden" name="infid" value="'.$val["infid"].'"/>
						<input type="submit" value="List" class = "tabsubmit" />
					</form>
				</td>
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

</div>

<?php include('footer.htm'); ?>

</body>
</html>