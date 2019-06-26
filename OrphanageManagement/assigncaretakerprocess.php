<?php
if($_SERVER["REQUEST_METHOD"]=="POST") {
	include('connect.php');
	
	$infid = testData($_POST["infid"]);
	$empid = testData($_POST["empid"]);
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "USE $dbname";
		$conn->beginTransaction();
		
		$conn->exec($sql);
		
		$sql = "UPDATE infant
				SET caretakerid='$empid'
				WHERE infid='$infid'";
		
		$conn->exec($sql);
		//echo $empid;
		//echo $infid;
		
		$conn->commit();
		$conn = null;
		
		echo '<form name="assign" method="post" action="assigncaretaker.php">
				<input type="hidden" name="empid" value="'.$_POST["oldempid"].'" />
				<input type="hidden" name="name" value="'.$_POST["name"].'"/>
				</form>
				<script>
					window.assign.submit();
				</script>';
	}
	catch(PDOException $e){
		$conn->rollback();
		echo $e->getMessage();
	}
}

?>