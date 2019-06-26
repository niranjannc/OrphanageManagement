<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Adoption Details under Orphanage" />
<meta name = "keywords" content = "Adoption" />
<meta name = "viewport" content = "width=device-width, initial-scale=1.0" />

<link rel = "stylesheet" type = "text/css" href = "viewtable.css" />

<title>Adoption Details</title>

<style>
	.tabsubmit {
		padding : 5px;
		cursor : pointer;
		font-size : 80%;
		width : 80px;
		background-color : transparent;
		color : #9999ff;
		border-color : #9999ff;
	}
	.tabsubmit:hover {
		color : white;
		background-color : #00aaff;
		border-color : transparent;
	}
</style>
</head>

<body>

<?php

include('connect.php');
$infid = testData($_POST["infid"]);
$name = ucwords(testData($_POST["name"]));
$adoptdate = testData($_POST["adoptdate"]);

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname";
	$sql1 = "SELECT p.pid, p.fname, p.mname, e.fname as headfname, e.lname, i.photo as infphoto, p.photo, a.approvehead
			FROM adoption a, parents p, employee e, infant i
			WHERE a.pid=p.pid AND a.approvehead=e.empid
				AND a.infid=i.infid
				AND a.infid = '$infid'";
	
	$conn->beginTransaction();
	$conn->exec($sql);
	$stmt = $conn->prepare($sql1);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	//print_r($arr);
	$val = $arr[0];
	$fname = ucwords($val["fname"]);
	$mname = ucwords($val["mname"]);
	$head = ucwords($val["headfname"]." ".$val["lname"]);
	$parentsphoto = $val["photo"];
	$infantphoto = $val["infphoto"];
	
	if($parentsphoto == ""){
		$parentsphoto = "default.png";
		$parentsphoto = file_get_contents($parentsphoto);
		$parentsphoto = base64_encode($parentsphoto);
	}
	if($infantphoto == ""){
		$infantphoto = "default.png";
		$infantphoto = file_get_contents($infantphoto);
		$infantphoto = base64_encode($infantphoto);
	}
	if($fname == "") 
		$fname = "---";
	if($mname == "") 
		$mname = "---";
	
	$adoptdate = date("d-m-Y", strtotime($adoptdate));
	
	$conn->commit();
	$conn=null;
}
catch(PDOException $e) {
	$conn->rollback();
	echo $e->getMessage();
}

?>
	<h1 align = "center">ADOPTION DETAILS</h1>
	<div class = "viewcontents" style = "width : 20%; margin-left:70px;">
		<table>
			<tr>
				<img src = "data:image; base64, <?php echo $infantphoto; ?>" width = "200px" height = "200px" />
			</tr><br />
			<h3 style="margin-left:70px;color:blue;">Infant</h3>
		</table>
	</div>
	<div class = "viewcontents" style = "width : 50%;">
		<table>
			<tr>
				<th >Infant Name</th>
				<td width="40%"><?php echo $name; ?></td>
				<td>
					<form method = "POST" action = "leftinfantdetails.php" target = "_blank">
						<input type="hidden" name="infid" value="<?php echo $infid;?>"/>
						<input type="submit" value="View" class = "tabsubmit" />
					</form>
				</td>
			</tr>
			<tr>
				<th >Father Name</th>
				<td id = "fname" width="40%"><?php echo $fname; ?></td>
				<td>
					<form name="fview" method = "POST" action = "parentsdetails.php" target = "_blank">
						<input type="hidden" name="pid" value="<?php echo $val["pid"];?>"/>
						<input type="button" value="View" class = "tabsubmit" onclick = "fvalidate()" />
					</form>
				</td>
			</tr>
			<tr>
				<th >Mother Name</th>
				<td id = "mname" width="40%"><?php echo $mname; ?></td>
				<td>
					<form name="mview" method = "POST" action = "parentsdetails.php" target = "_blank">
						<input type="hidden" name="pid" value="<?php echo $val["pid"];?>"/>
						<input type="button" value="View" class = "tabsubmit" onclick = "mvalidate()" />
					</form>
				</td>
			</tr>
			<tr>
				<th >Approve Head</th>
				<td width="40%"><?php echo $head; ?></td>
				<td>
					<form method = "POST" action = "employeedetails.php" target = "_blank">
						<input type="hidden" name="empid" value="<?php echo $val["approvehead"];?>"/>
						<input type="submit" value="View" class = "tabsubmit" />
					</form>
				</td>
			</tr>
			<tr>
				<th>Adoption Date</th>
				<td colspan="2"><?php echo $adoptdate; ?></td>
			</tr>
		</table>
	</div>
	<div class = "viewcontents" style = "width : 20%; margin-left:50px;">
		<table>
			<tr>
				<img src = "data:image; base64, <?php echo $parentsphoto; ?>" width = "200px" height = "200px" />
			</tr>
			<h3 style="margin-left:70px;color:blue;">Parents</h3>
		</table>
	</div>
	<?php include('footer.htm'); ?>
<script>
	function fvalidate() {
		if(document.getElementById('fname').innerHTML=="---") {
			alert("Father field is empty. Cannot Display");
		}
		else
			document.fview.submit();
	}
	function mvalidate() {
		if(document.getElementById('mname').innerHTML=="---") {
			alert("Mother field is empty. Cannot Display");
		}
		else
			document.mview.submit();
	}
</script>
</body>
</html>