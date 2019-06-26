<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Old Employee List under Orphanage" />
<meta name = "keywords" content = "Old Employee, List Old Employee" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Old Employee</title>

</head>

<body>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" onclick = "window.location.href = 'employeelist.php'">Employees List</button></li>
<li><button type = "button" onclick = "window.location.href = 'employeeform.php'">Add Employee</button></li>
<li><button type = "button" >Old Employees</button></li>
</ul>
</div>

<h1>OLD EMPLOYEES LIST</h1>

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
	
	$sql = "SELECT empid, fname, lname
			FROM employee
			WHERE orgid = '$orgid'
				AND hasleft = 'y'
			ORDER BY empid";
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	echo "<table style = 'width:70%'>
			<tr>
				<th width='60%'>Name</th>
				<th>Details</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td>
					<form method = "POST" action = "oldemployeedetails.php" target = "_blank">
						<input type="hidden" name="empid" value="'.$val["empid"].'"/>
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