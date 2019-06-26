<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Employee List under Orphanage" />
<meta name = "keywords" content = "Employee, List Employee" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Employee</title>

</head>

<body>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" >Employees List</button></li>
<li><button type = "button" onclick = "window.location.href = 'employeeform.php'">Add Employee</button></li>
<li><button type = "button" onclick = "window.location.href = 'oldemployeelist.php'">Old Employees</button></li>
</ul>
</div>

<h1>EMPLOYEES LIST</h1>

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
	
	$sql = "SELECT empid, fname, lname, designation
			FROM employee
			WHERE orgid = '$orgid'
				AND hasleft = 'n'
			ORDER BY empid";
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	echo "<table>
			<tr>
				<th width='40%'>Name</th>
				<th width='35%'>Designation</th>
				<th>Details</th>
				<th>Edit</th>
			</tr>";
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td class="tabfont">'.ucwords($val["designation"]).'</td>
				<td>
					<form method = "POST" action = "employeedetails.php" target = "_blank">
						<input type="hidden" name="empid" value="'.$val["empid"].'"/>
						<input type="submit" value="View" class = "tabsubmit" />
					</form>
				</td>
				<td>
					<form method = "POST" action = "employeeedit.php">
						<input type="hidden" name="empid" value="'.$val["empid"].'"/>
						<input type="hidden" name="name" value="'.$val["fname"].' '.$val["lname"].'"/>
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