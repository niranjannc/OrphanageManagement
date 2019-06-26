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
<meta name = "viewport" content = "width=device-width, initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Parents</title>

</head>

<body>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" onclick = "window.location.href = 'adoptionlist.php'">Adoption List</button></li>
<li><button type = "button" >Parents List</button></li>
<li><button type = "button" onclick = "window.location.href = 'parentform.php'">Add Parents</button></li>
</ul>
</div>

<h1>PARENTS LIST</h1>

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
	
	$sql = "SELECT pid, fname, mname, agereq, genreq
			FROM parents
			WHERE orgid = '$orgid'
				AND pid NOT IN(SELECT a.pid from adoption a)";
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	echo "<table style = 'width:100%'>
			<tr>
				<th width='40%'>Parents</th>
				<th width='10%'>Age Requirement</th>
				<th width='10%'>Gender Requirement</th>
				<th width='20%'>Details</th>
				<th width='20%'>Search Infants</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"])."<br />".ucwords($val["mname"]).'</td>
				<td class="tabfont">'.($val["agereq"]-1).' - '.($val["agereq"]+1).'</td>
				<td class="tabfont">';
					if($val["genreq"]=="m")
						echo "Male";
					else if($val["genreq"]=="f")
						echo "Female";
					else if($val["genreq"]=="o")
						echo "Others";
				echo '</td>
				<td>
					<form method = "POST" action = "parentsdetails.php" target = "_blank">
						<input type="hidden" name="pid" value="'.$val["pid"].'"/>
						<input type="submit" value="View" class = "tabsubmit" />
					</form>
				</td>
				<td>
					<form method = "POST" action = "parentssearch.php">
						<input type="hidden" name="pid" value="'.$val["pid"].'"/>
						<input type="hidden" name="fname" value="'.$val["fname"].'"/>
						<input type="hidden" name="mname" value="'.$val["mname"].'"/>
						<input type="hidden" name="agereq" value="'.$val["agereq"].'"/>
						<input type="hidden" name="genreq" value="'.$val["genreq"].'"/>
						<input type="submit" value="Search" class = "tabsubmit" />
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