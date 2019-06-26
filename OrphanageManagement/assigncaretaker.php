<?php
session_start();
$name = $_POST["name"];
$empid = $_POST["empid"];
$status="";
?>

<html>
<head>
	<title>Assign Caretaker</title>
	<link rel="stylesheet" type="text/css" href="table.css" />
	
	<style>
		select {
			width : 75%;
			border-radius : 6px;
			border-collapse : none;
			font-size : 110%;
			height : 35px;
			margin-top : 10px;
		}
	</style>
</head>
				
<body>
	<h1 style="color:green" align="center">ASSIGN CARETAKER</h1>
	<h2 align = "center">Assign caretakers for the infants whose caretaker was <?php echo $name;?></h2>
	
	<table style="width:65%" align="center">
	<tr>
		<th width="30%">Infants</th>
		<th width="30%">Employees</th>
		<th width="30%">Assign</th>
	</tr>
	
	<?php
	include('connect.php');
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "use $dbname";
		$conn->exec($sql);
		
		include('orgid.php');
		$sql = "SELECT count(infid) as cnt
				FROM infant
				WHERE caretakerid = '$empid'
					AND hasleft = 'n' AND orgid = '$orgid'";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$arr = $stmt->fetchAll();
		//print_r($arr);
		$infantcount = $arr[0]["cnt"];
		
		if($infantcount==0) {
			echo '<script>
					alert("Updated Sucessfully");
					window.location.href="employeelist.php";
				</script>';
		}
		
		$sql = "SELECT count(empid) as cnt
				FROM employee
				WHERE hasleft = 'n'
					AND orgid = '$orgid'";
				
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$arr = $stmt->fetchAll();
		//print_r($arr);
		$employeecount = $arr[0]["cnt"];
		
		if($employeecount==0) {
			$sql = "UPDATE infant
					SET caretakerid=null
					WHERE caretakerid='$empid'";
			$conn->exec($sql);
			
			echo '<script>
					alert("Updated Sucessfully.\nThere are no employees to be assigned as caretaker.\nNo. of infants without caretaker : '.$infantcount.'");
					window.location.href="employeelist.php";
				</script>';
		}
		
		$sql = "SELECT infid, fname, lname
			FROM infant
			WHERE caretakerid = '$empid'
				AND hasleft = 'n' AND orgid = '$orgid'";
						
		$sql1 = "SELECT empid, fname, lname
			FROM employee
			WHERE orgid='$orgid'
				AND hasleft='n'";
						
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$arr1 = $stmt->fetchAll();
			
		$stmt = $conn->prepare($sql1);
		$stmt->execute();
		$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$arr2 = $stmt->fetchAll();
		
		//print_r($arr1);
		
		foreach($arr1 as $val1) {
			echo '<tr>
				<form method = "post" action = "'.htmlspecialchars("assigncaretakerprocess.php").'">
					<td class = "tabfont">
						<input type = "hidden" name = "infid" value = "'.$val1["infid"].'" />
								'.$val1["fname"].' '.$val1["lname"].'
					</td>
					<td>
						<select name = "empid" required>
							<option value="">--Select--</option>';
							foreach($arr2 as $val2) {
								echo '<option value="'.$val2["empid"].'">'.$val2["fname"].' '.$val2["lname"].'</option>';
								}
						echo '
						</select>
					</td>
					<td>
						<input type = "hidden" name = "name" value = "'.$name.'" />
						<input type = "hidden" name = "oldempid" value = "'.$empid.'" />
						<input type = "Submit" value = "Submit" class = "tabsubmit"/>
					</td>
				</form>
			</tr>';
		}
	}
	catch(PDOException $e) {
		$conn->rollback();
		echo $e->getMessage();
	}
	?>
			
				
	</table>
	<?php include('footer.htm'); ?>
</body>
</html>

		