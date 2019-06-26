<?php
include('connect.php');
$status = "";

if(isset($_REQUEST["assigncaretaker"])) {
	$infid = testData($_POST["infid"]);
	$caretaker = testData($_POST["caretaker"]);
	
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "use $dbname";
		$sql1 = "UPDATE infant
				SET caretakerid = '$caretaker'
				WHERE infid = '$infid'";
						
		$conn->beginTransaction();
		
		//echo "e1";
		
		$conn->exec($sql);
		$conn->exec($sql1);
		
		//echo "e3";
		$status = true;
		//echo "e4";
		$conn->commit();
		$conn = null;
		//echo "e5";
	}
	catch(PDOException $e){
		//echo "e6";
		$conn->rollback();
		//echo "e7";
		echo $e->getMessage();
	}
}
if(isset($_REQUEST["reportleaving"])) {
	$infid = testData($_POST["infid"]);
	$leavedate = testData($_POST["leavedate"]);
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "use $dbname";
		$sql1 = "UPDATE infant
				SET hasleft = 'y'
				WHERE infid = '$infid'";
		$sql2 = "UPDATE infant
				SET leavedate = '$leavedate'
				WHERE infid = '$infid'";
				
		//echo "$orgid<br />$phone";
		
		$conn->beginTransaction();
		
		$conn->exec($sql);
		$conn->exec($sql1);
		$conn->exec($sql2);
		
		$status = true;
		
		$conn->commit();
		$conn = null;
			
	}
	catch(PDOException $e){
		$conn->rollback();
		echo $e->getMessage();
	}
}


if($status === true) {
	echo '<script>
				alert("Sucessfully Updated");
				window.location.href = "infantslist.php";
			</script>';
}

?>