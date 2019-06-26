<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Adoption List under Orphanage" />
<meta name = "keywords" content = "Adoption, List Adoption" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Adoption</title>

</head>

<body>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" >Adoption List</button></li>
<li><button type = "button" onclick = "window.location.href = 'parentslist.php'">Parents List</button></li>
<li><button type = "button" onclick = "window.location.href = 'parentform.php'">Add Parents</button></li>
</ul>
</div>

<h1>ADOPTION LIST</h1>

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
	
	$sql = "SELECT a.infid, i.fname, i.lname, a.adoptdate
			FROM adoption a, infant i
			WHERE a.infid=i.infid
				AND i.orgid = '$orgid'
			ORDER BY adoptdate DESC";
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	echo "<table>
			<tr>
				<th width='40%'>Infant Name</th>
				<th width='35%'>Adopt Date</th>
				<th>Details</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td class="tabfont">'.date("d-m-Y",strtotime($val["adoptdate"])).'</td>
				<td>
					<form method = "POST" action = "adoptiondetails.php" target = "_blank">
						<input type="hidden" name="infid" value="'.$val["infid"].'"/>
						<input type="hidden" name="name" value="'.$val["fname"]." ".$val["lname"].'"/>
						<input type="hidden" name="adoptdate" value="'.$val["adoptdate"].'"/>
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