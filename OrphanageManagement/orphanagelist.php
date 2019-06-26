<?php
session_start();
include('adminlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Orphanage List under Trust" />
<meta name = "keywords" content = "Orphanages, List Orphanages" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Orphanages </title>

</head>

<body>

<div>
<?php
include( 'adminlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" >Orphanage List</button></li>
<li><button type = "button" onclick = "window.location.href = 'orphanageform.php'">Add Orphanage</button></li>
</ul>
</div>

<h1>ORPHANAGE LIST</h1> 

<div class = "tabcontent">

<?php

include('connect.php');

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	$sql1 = "SELECT orgid, name FROM orphanage";
	
	$conn->beginTransaction();
	$conn->exec($sql);
	$stmt = $conn->prepare($sql1);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	echo "<table>
			<tr>
				<th width = '50%'>Name</th>
				<th>Details</th>
				<th>Edit</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class = "tabfont">'.ucwords($val["name"]).'</td>
				<td>
					<form method = "POST" action = "orphanagedetails.php" target = "_blank">
						<input type="hidden" name="view" value="'.$val["orgid"].'"/>
						<input type="submit" value="View" class = "tabsubmit" />
					</form>
				</td>
				<td>
					<form method = "POST" action = "orphanageedit.php">
						<input type="hidden" name="orgid" value="'.$val["orgid"].'"/>
						<input type="hidden" name="oname" value="'.$val["name"].'"/>
						<input type="submit" value="Edit" class = "tabsubmit" />
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