<?php
session_start();
include('adminlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Donors, Trustees List under Trust" />
<meta name = "keywords" content = "Donors, Trustees, List Donors" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Donors and Trustees</title>

</head>

<body>

<div>
<?php
include( 'adminlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" >Donors & Trustees List</button></li>
<li><button type = "button" onclick = "window.location.href = 'donorform.php'">Add Donors/Trustees</button></li>
<li><button type = "button" onclick = "window.location.href = 'donationlist.php'">Donation List</button></li>
</ul>
</div>

<h1>DONORS LIST</h1>

<div class = "tabcontent">

<?php

include('connect.php');

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	$sql1 = "SELECT o.orgid, o.name, d.did, d.fname, d.lname
			FROM donor d, orphanage o
			WHERE o.orgid = d.orgid
			ORDER BY d.did";
	
	$conn->beginTransaction();
	$conn->exec($sql);
	$stmt = $conn->prepare($sql1);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	
	echo "<table>
			<tr>
				<th width='35%'>Orphanage</th>
				<th width='35%'>Name</th>
				<th>Details</th>
				<th>Add Donation</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["name"]).'</td>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td>
					<form method = "POST" action = "donordetails.php" target = "_blank">
						<input type="hidden" name="did" value="'.$val["did"].'"/>
						<input type="hidden" name="orgname" value="'.$val["name"].'"/>
						<input type="submit" value="View" class = "tabsubmit" />
					</form>
				</td>
				<td>
					<form method = "POST" action = "donationform.php">
						<input type="hidden" name="did" value="'.$val["did"].'"/>
						<input type="hidden" name="name" value="'.$val["fname"].' '.$val["lname"].'"/>
						<input type="submit" value="Donation" class = "tabsubmit" />
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