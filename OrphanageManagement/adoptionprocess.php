<?php
include('connect.php');
$status = "";

if($_SERVER["REQUEST_METHOD"] == "POST" ) {
	$infid = testData($_POST["infid"]);
	$pid = testData($_POST["pid"]);
	$empid = testData($_POST["empid"]);
	$adoptdate = testData($_POST["adoptdate"]);
	
	try {
		$conn = new PDO("mysql:host=$server;dbname=$dbname",$username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		$conn->beginTransaction();
		
		$sql = "INSERT INTO adoption VALUES('$pid', '$infid', '$adoptdate', '$empid')";
		$conn->exec($sql);
		
		$sql = "UPDATE infant
				SET hasleft='y'
				WHERE infid='$infid'";
		$conn->exec($sql);
		
		$sql = "UPDATE infant
				SET leavedate='$adoptdate'
				WHERE infid='$infid'";
		$conn->exec($sql);
		
		$conn->commit();
		$conn=null;
		
		echo '<script>
				alert("Adoption Process Complete");
				window.location.href = "adoptionlist.php";
			</script>';
	}
	catch(PDOException $e) {
		$conn->rollback();
		echo $e->getMessage();
	}
}
?>