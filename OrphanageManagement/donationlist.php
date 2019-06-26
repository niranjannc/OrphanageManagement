<?php
session_start();
include('adminlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Donation, Donation List under Trust" />
<meta name = "keywords" content = "Donation, Orphange Donation" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Donation List</title>

</head>

<body>

<div>
<?php
include( 'adminlink.htm' );
?>
</div>

<div class = "nestedlinks" width = "20%">
<ul>
<li><button type = "button" onclick = "window.location.href = 'donorlist.php'">Donors & Trustees List</button></li>
<li><button type = "button" onclick = "window.location.href = 'donorform.php'">Add Donors/Trustees</button></li>
<li><button type = "button" >Donation List</button></li>
</ul>
</div>

<h1>DONATION LIST</h1>

<div class = "tabcontent" width = "100%">

<?php

include('connect.php');

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	$sql1 = "SELECT o.name, d.fname, d.lname, dn.amount, dn.paymode, dn.service, dn.purpose, dn.ddate
			FROM donor d, orphanage o, donation dn
			WHERE o.orgid = d.orgid AND d.did = dn.did
			ORDER BY ddate DESC";
	
	$conn->beginTransaction();
	$conn->exec($sql);
	$stmt = $conn->prepare($sql1);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	
	echo "<table style = 'width : 100%'>
			<tr>
				<th width='20%'>Orphanage</th>
				<th width='20%'>Name</th>
				<th width='15%'>Amount/Service</th>
				<th width = '20%'>Purpose</th>
				<th width='25%'>Date</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["name"]).'</td>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td class="tabfont">';
					if($val["amount"] == 0) {
						echo ucwords($val["service"]);
					}
					if($val["service"] == "") {
						echo ucwords($val["amount"]).' through '.$val["paymode"];
					}
				echo '</td>
				<td class="tabfont">';
					if($val["purpose"] == "") {
						echo '---';
					}
					else
						echo ucwords($val["purpose"]);
				echo '</td>
				<td class="tabfont">';
					echo date('d-m-Y', strtotime($val["ddate"]));
				echo '</td>
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