<?php
include('connect.php');
$status = "";

if(isset($_REQUEST["changeaddress"])) {
	$orgid = testData($_POST["orgid"]);
	$addlocation = testData($_POST["addlocation"]);
	$addcity = testData($_POST["addcity"]);
	$adddistrict = testData($_POST["adddistrict"]);
	$addstate = testData($_POST["addstate"]);
	
	/*echo $addlocation."<br />";
	echo $addcity."<br />";
	echo $adddistrict."<br />";
	echo $addstate."<br />";
	echo $orgid."<br />";*/
	
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "use $dbname";
		$sql1 = "UPDATE orphanage
				SET addlocation = '$addlocation'
				WHERE orgid = '$orgid'";
		$sql2 = "UPDATE orphanage
				SET addcity = '$addcity'
				WHERE orgid = '$orgid'";
		$sql3 = "UPDATE orphanage
				SET adddistrict = '$adddistrict'
				WHERE orgid = '$orgid'";
		$sql4 = "UPDATE orphanage
				SET addstate = '$addstate'
				WHERE orgid = '$orgid'";
				
		$conn->beginTransaction();
		
		//echo "e1";
		
		$conn->exec($sql);
		$conn->exec($sql1);
		$conn->exec($sql2);
		$conn->exec($sql3);
		$conn->exec($sql4);
		
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
if(isset($_REQUEST["changephone"])) {
	$orgid = testData($_POST["orgid"]);
	$phone = testData($_POST["phone"]);
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "use $dbname";
		$sql1 = "UPDATE orphanage
				SET phone = '$phone'
				WHERE orgid = '$orgid'";
				
		//echo "$orgid<br />$phone";
		
		$conn->beginTransaction();
		
		$conn->exec($sql);
		$conn->exec($sql1);
		
		$status = true;
		
		$conn->commit();
		$conn = null;
			
	}
	catch(PDOException $e){
		$conn->rollback();
		echo $e->getMessage();
	}
}

if(isset($_REQUEST["changeemail"])) {
	$orgid = testData($_POST["orgid"]);
	$email = testData($_POST["email"]);
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "use $dbname";
		$sql1 = "UPDATE orphanage
				SET email = '$email'
				WHERE orgid = '$orgid'";
		
		$conn->beginTransaction();
		
		$conn->exec($sql);
		$conn->exec($sql1);
		
		$status = true;
		
		$conn->commit();
		$conn = null;
			
	}
	catch(PDOException $e){
		$conn->rollback();
		echo $e->getMessage();
	}	
}

if(isset($_REQUEST["changehead"])) {
	$orgid = testData($_POST["orgid"]);
	$head = testData($_POST["head"]);
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "use $dbname";
		$sql1 = "UPDATE orphanage
				SET head = '$head'
				WHERE orgid = '$orgid'";
		
		$conn->beginTransaction();
		
		$conn->exec($sql);
		$conn->exec($sql1);
		
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
				window.location.href = "orphanagelist.php";
			</script>';
}

?>