<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Donors, Trustees List under Orphanage" />
<meta name = "keywords" content = "Donors, Trustees, List Donors" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Donors & Trustees</title>

</head>

<body>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" >Donors & Trustees List</button></li>
<li><button type = "button" onclick = "window.location.href = 'donor_form.php'">Add Donors/Trustees</button></li>
<li><button type = "button" onclick = "window.location.href = 'donation_list.php'">Donation List</button></li>
<li><button type = "button" onclick = "window.location.href = 'sponser_list.php'">Sponsered List</button></li>
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
	
	$conn->beginTransaction();
	$conn->exec($sql);
	include('orgid.php');
	
	$sql2 = "SELECT did, fname, lname
			FROM donor
			WHERE orgid = '$orgid'
			ORDER BY did";
	
	$stmt = $conn->prepare($sql2);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	
	echo "<table>
			<tr>
				<th width='40%'>Name</th>
				<th>Details</th>
				<th>Add Donation</th>
				<th>View Sponsered List</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td>
					<form method = "POST" action = "donor_details.php" target = "_blank">
						<input type="hidden" name="did" value="'.$val["did"].'"/>
						<input type="submit" value="View" class = "tabsubmit" />
					</form>
				</td>
				<td>
					<form method = "POST" action = "donation_form.php">
						<input type="hidden" name="did" value="'.$val["did"].'"/>
						<input type="hidden" name="name" value="'.$val["fname"].' '.$val["lname"].'"/>
						<input type="submit" value="Donation" class = "tabsubmit" />
					</form>
				</td>
				<td>
					<form method = "POST" action = "dsponser_list.php" target = "_blank">
						<input type="hidden" name="did" value="'.$val["did"].'"/>
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