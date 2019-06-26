<?php
$status = "";

if($_SERVER["REQUEST_METHOD"] == "POST" ) {
	try {
		include('connect.php');
		
		$infid = testData($_POST["infid"]);
		$did = testData($_POST["donor"]);
		
		$conn = new PDO("mysql:host = $server;dbname = $dbname",$username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		$conn->beginTransaction();
		
		$sql = "use $dbname";
		$conn->exec($sql);
		
		//echo $infid;
		//echo $did;
		
		$sql = "INSERT INTO sponser VALUES('$infid', '$did')";
		$conn->exec($sql);
		
		$status = true;
		
		$conn->commit();
		$conn = null;
		
		if($status === true) {
			echo '<script>alert("Successfully Registered");
						window.location.href = "infantslist.php";
				</script>';
		}
	}
	catch(PDOException $e) {
		$conn->rollback();
		echo $e->getMessage();
	}
}
?>