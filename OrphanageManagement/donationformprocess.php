<?php
$totalcontribution=$oldcontribution= 0;

if(isset($_POST["donation"])) {
	include('connect.php');
	
	$did = testData($_POST["did"]);
	$amount = testData($_POST["amount"]);
	$mode = testData($_POST["mode"]);
	$service = testData($_POST["service"]);
	$purpose = testData($_POST["purpose"]);
	$ddate = testData($_POST["ddate"]);
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "USE $dbname";
		$sql1 = "INSERT INTO donation values('$did','$amount','$mode','$service','$purpose','$ddate')";
		$sql2 = "SELECT contribution FROM donor WHERE did = '$did'";
		
		$conn->beginTransaction();
		
		$conn->exec($sql);
		$conn->exec($sql1);
		
		$stmt = $conn->prepare($sql2);
		$stmt->execute();
		
		$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$arr = $stmt->fetchAll();
		//print_r($arr);
		//echo "<br />";
		
		$val = $arr[0];
		//print_r($val);
		//echo "<br />";
		
		if($val["contribution"] == null) {
			$oldcontribution = 0;
		}
		else {
			$oldcontribution = $val["contribution"];
		}
		/*$oldcontribution = $val["contribution"];
		var_dump($val["contribution"]);
		//var_dump($oldcontribution);
		var_dump($amount);*/
		
		if($amount != null) {
			$totalcontribution = $amount + $oldcontribution;
			//echo $totalcontribution;
			$sql3 = "UPDATE donor set contribution = $totalcontribution WHERE did = '$did'";
			$conn->exec($sql3);
		}
		
		//var_dump($totalcontribution);
		
		echo "<script>
				alert('Donation registered Successfully');
				document.location.href = 'donorlist.php';
			</script>";
		
		$conn->commit();
		$conn = null;
	}
	catch(PDOException $e){
		$conn->rollback();
		echo $e->getMessage();
	}
}

?>