<?php
//Starting Session
session_start();
?>

<!doctype html>

<html>

<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Login Page" />
<meta name = "keywords" content = "Login Page" />
<meta name = "viewport" content = "width=device-width, initial-scale = 1.0" />

<title>Login Page</title>

<link rel = "stylesheet" type = "text/css" href = "span_input.css" />
<link rel = "stylesheet" type = "text/css" href = "fieldset.css" />

<!--
<style>

#image {
	background-image : url("images.jpg");
	background-repeat : no-repeat;
	background-position : center;
	background-size : 500px 500px;
}

</style>-->

</head>

<body >

<?php 

$uname=$pwd=$unameerr=$pwderr=$status="";

if( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	include('connect.php');
	
	$uname = $_POST["username"];
	$pwd = $_POST["password"];
	
	$uname = testData($uname);
	$pwd = testData($pwd);
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		$conn->beginTransaction();
		
		$sql = "USE $dbname";
		$conn->exec($sql);
		
		$stmt = $conn->prepare("SELECT * from login");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$arr = $stmt->fetchAll();
		
		if($arr == null) {
			$unameerr = "Username doesn't exists";
		}
		
		foreach($arr as $value) {
			$unameerr = "";
			if($value["username"] == $uname) {
				if($value["password"] == $pwd) {
					$status = true;
					$role = $value["role"];
					$id = $value["id"];
					break;
				}
				else {
					$status = false;
					$pwderr = "Wrong Password!";
					break;
				}
			}
			else {
				$status = false;
				$unameerr = "Username doesn't exists";
			}
		}
		
		$conn->commit();
		$conn = null;
	}
	catch(PDOException $e) {
		$conn->rollback();
		echo $e->getMessage();
	}
	
}

?>

<div align = "center" style = "margin : 20px;">

	<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >

		<fieldset>
			<legend>LOGIN</legend>

			<div align = "left" id = "image">

				<span >* required</span><br /><br />

				<label for = "username">Username</label><br />
				<input type = "text" id = "username" name = "username" placeholder = "Username" value = "<?php echo $uname; ?>" required autocomplete = "off" /><span> * <?php echo $unameerr; ?></span><br /><br />

				<label for = "password">Password</label><br />
				<input type = "password" id = "password" name = "password" placeholder = "Password" value = "<?php echo $pwd; ?>" required autocomplete = "off" /><span> * <?php echo $pwderr; ?></span><br /><br />

				<section align = "center">
					<input type = "Submit" value = "Submit" /><br /><br />
				</section>

			</div>

		</fieldset>

	</form>

</div>

<?php
if($status === true) {
	if($role == "admin") {
		$_SESSION["adminstate"] = "active";
		header ( 'Location: https://localhost/dbproject/admin.php' );
		exit();
	}
	if($role == "head") {
		$_SESSION["headstate"] = "active";
		$_SESSION["id"] = $id;
		header ( 'Location: https://localhost/dbproject/head.php' );
		exit();
	}
}

?>
</body>

</html>