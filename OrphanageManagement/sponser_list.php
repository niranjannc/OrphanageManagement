<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Sponsered List under Orphanage" />
<meta name = "keywords" content = "Sponsers List Sponsers" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Sponsered List</title>

</head>

<body>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" onclick = "window.location.href = 'donor_list.php'">Donors & Trustees List</button></li>
<li><button type = "button" onclick = "window.location.href = 'donor_form.php'">Add Donors/Trustees</button></li>
<li><button type = "button" onclick = "window.location.href = 'donation_list.php'">Donation List</button></li>
<li><button type = "button" >Sponsered List</button></li>
</ul>
</div>

<h1>SPONSERED LIST</h1>

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
	
	$sql2 = "SELECT d.fname as dfname, d.lname as dlname, i.fname, i.lname, e.fname as efname, e.lname as elname
			FROM infant i, employee e, sponser s, donor d
			WHERE s.infid = i.infid AND i.caretakerid = e.empid
				AND s.did = d.did AND i.orgid = '$orgid'";
	
	$stmt = $conn->prepare($sql2);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	
	echo "<table>
			<tr>
				<th width='33.5%'>Sponserer</th>
				<th width='33.5%'>Infant</th>
				<th width='33.5%'>Caretaker</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["dfname"])." ".ucwords($val["dlname"]).'</td>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
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

</div><br />
</body>
<?php include('footer.htm'); ?>
</html>