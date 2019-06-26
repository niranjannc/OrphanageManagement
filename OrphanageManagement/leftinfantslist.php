<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Old Infants under Orphanage" />
<meta name = "keywords" content = "Old Infants, List Old Infants" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Old Infants</title>

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
<li><button type = "button" onclick = "window.location.href = 'requiresponser.php'">Require Sponsering</button></li>
<li><button type = "button" >Left Infants List</button></li>
</ul>
</div>

<h1>LEFT INFANTS LIST</h1>

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
			WHERE orgid = '$orgid' AND hasleft = 'y'
			ORDER BY infid";
	
	$stmt = $conn->prepare($sql2);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	
	echo "<table style = 'width:70%'>
			<tr>
				<th width='40%'>Name</th>
				<th width='20%'>Details</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td>
					<form method = "POST" action = "leftinfantdetails.php" target = "_blank">
						<input type="hidden" name="infid" value="'.$val["infid"].'"/>
						<input type="submit" value="View" class = "tabsubmit" />
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