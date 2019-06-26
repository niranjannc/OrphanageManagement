<?php
include('connect.php');
$status = "";

if(isset($_REQUEST["changeaddress"])) {
	$empid = testData($_POST["empid"]);
	$addlocation = testData($_POST["addlocation"]);
	$addcity = testData($_POST["addcity"]);
	$adddistrict = testData($_POST["adddistrict"]);
	$addstate = testData($_POST["addstate"]);
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$conn->beginTransaction();
		
		$sql = "use $dbname";
		$conn->exec($sql);
		
		$sql = "UPDATE employee
				SET addlocation='$addlocation'
				WHERE empid='$empid'";
		$conn->exec($sql);
		
		$sql = "UPDATE employee
				SET addcity='$addcity'
				WHERE empid='$empid'";
		$conn->exec($sql);
		
		$sql = "UPDATE employee
				SET adddistrict='$adddistrict'
				WHERE empid='$empid'";
		$conn->exec($sql);
		
		$sql = "UPDATE employee
				SET addstate='$addstate'
				WHERE empid='$empid'";
		$conn->exec($sql);
		
		$status=true;
		
		$conn->commit();
		$conn=null;
	}
	catch(PDOException $e){
		$conn->rollback();
		echo $e->getMessage();
	}
}

if(isset($_REQUEST["changephone"])) {
	$empid = testData($_POST["empid"]);
	$phone = testData($_POST["phone"]);
	
	try{
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$conn->beginTransaction();
		
		$sql = "use $dbname";
		$conn->exec($sql);
		
		$sql = "UPDATE employee
				SET phone='$phone'
				WHERE empid='$empid'";
		$conn->exec($sql);
		
		$status=true;
		
		$conn->commit();
		$conn=null;
	}
	catch(PDOException $e){
		$conn->rollback();
		echo $e->getMessage();
	}
}

if(isset($_REQUEST["changedesignation"])) {
	$empid = testData($_POST["empid"]);
	$designation = testData($_POST["designation"]);
	
	try{
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$conn->beginTransaction();
		
		$sql = "use $dbname";
		$conn->exec($sql);
		
		$sql = "UPDATE employee
				SET designation='$designation'
				WHERE empid='$empid'";
		$conn->exec($sql);
		
		$status=true;
		
		$conn->commit();
		$conn=null;
	}
	catch(PDOException $e){
		$conn->rollback();
		echo $e->getMessage();
	}
}

if(isset($_REQUEST["changesalary"])) {
	$empid = testData($_POST["empid"]);
	$salary = testData($_POST["salary"]);
	
	try{
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$conn->beginTransaction();
		
		$sql = "use $dbname";
		$conn->exec($sql);
		
		$sql = "UPDATE employee
				SET salary='$salary'
				WHERE empid='$empid'";
		$conn->exec($sql);
		
		$status=true;
		
		$conn->commit();
		$conn=null;
	}
	catch(PDOException $e){
		$conn->rollback();
		echo $e->getMessage();
	}
}

if(isset($_REQUEST["reportleaving"])) {
	$empid = testData($_POST["empid"]);
	$leavedate = testData($_POST["leavedate"]);
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname",$username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "use $dbname";
		$sql1 = "UPDATE employee
				SET hasleft = 'y'
				WHERE empid = '$empid'";
		$sql2 = "UPDATE employee
				SET leavedate = '$leavedate'
				WHERE empid = '$empid'";
				
		$conn->beginTransaction();
		
		$conn->exec($sql);
		$conn->exec($sql1);
		$conn->exec($sql2);
		
		$sql = "SELECT fname, lname
				FROM employee
				WHERE empid = '$empid'";
				
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		
		$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$arr = $stmt->fetchAll();
		
		$name = $arr[0]["fname"]." ".$arr[0]["lname"];
		//echo $name;
		
		$sql = "SELECT count(infid) as cnt
				FROM infant
				WHERE caretakerid = '$empid'
				AND hasleft = 'n'";
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		
		$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$arr = $stmt->fetchAll();
		
		$val = $arr[0]["cnt"];
		//echo $val;
		//print_r($_SESSION);
		
		$conn->commit();
		$conn = null;
		
		if($val != 0) {
			echo '<form name="assign" method="post" action="assigncaretaker.php">
				<input type="hidden" name="empid" value="'.$empid.'" />
				<input type="hidden" name="name" value="'.$name.'"/>
				</form>
				<script>
					window.assign.submit();
				</script>';
		}
		else {
			echo '<script>
					alert("Updated Sucessfully");
					window.location.href="employeelist.php";
				</script>';
		}
	}
	catch(PDOException $e){
		$conn->rollback();
		echo $e->getMessage();
	}
}

if($status === true) {
	echo '<script>
			alert("Updated Sucessfully");
			window.location.href="employeelist.php";
		</script>';
}
?>