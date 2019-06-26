<?php
session_start();
include('adminlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Change Password for Admin" />
<meta name = "keywords" content = "Change Password, Manage Accounts" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = 'stylesheet' type = "text/css" href = "fieldset.css" />
<link rel = 'stylesheet' type = "text/css" href = "span_input.css" />

<title>Manage Account</title>

</head>

<body>

<div>
<?php
include( 'adminlink.htm' );
?>
</div>

<?php
$pwd=$cpwd=$npwd=$pwderr=$cpwderr="";

if($_SERVER["REQUEST_METHOD"]=="POST") {
	include('connect.php');

	$uname = 'admin';
	$pwd = testData($_POST["password"]);
	$npwd = testData($_POST["newpassword"]);
	$cpwd = testData($_POST["cpassword"]);
	
	//print_r($_POST);
	
	if($npwd != $cpwd) {
		$cpwderr="Incorrect Password. Please Retype Password same as above.";
	}
	
	if($cpwderr == "") {
		try {
			$conn = new PDO("mysql:host = $server; dbname = $dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
			$conn->beginTransaction();
		
			$sql = "USE $dbname";
			$sql1 = "SELECT password from login where username = '$uname'";
			$sql2 = "UPDATE login SET password = '$npwd' WHERE username = '$uname'";
			
			$conn->exec($sql);
		
			$stmt = $conn->prepare($sql1);
			$stmt->execute();
		
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$arr = $stmt->fetchAll();
			//print_r($arr);
			
			$value = $arr[0];
			if($value["password"] == $pwd) {
				$status = true;
			}
			else {
				$status = false;
				$pwderr = "Incorrect Password";
			}
			
			if($status === true) {
				$conn->exec($sql2);
				$pwd=$npwd=$cpwd="";
				echo '<script>alert("Password Changed Successfully");</script>';
			}
		
			$conn->commit();
			$conn = null;
		}
		catch(PDOException $e) {
			$conn->rollback();
			echo $e->getMessage();
		}
	}
}

?>

<div align = "center" style = "margin : 20px;">

	<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >

		<fieldset style = "width : 30%">
			<legend>CHANGE PASSWORD</legend>

			<div align = "left">

				<span >* required</span><br /><br />

				<label for = "username">Username</label><br />
				<input type = "text" id = "username" name = "username" placeholder = "Username" value = "admin" required autocomplete = "off"  disabled /><br /><br />
				
				<label for = "password">Password</label><br />
				<input type = "password" id = "password" name = "password" placeholder = "Current Password" value = "<?php echo $pwd; ?>" required autocomplete = "off" /><span> *<?php echo $pwderr; ?> </span><br /><br />

				<label for = "newpassword">New Password</label><br />
				<input type = "password" id = "newpassword" name = "newpassword" placeholder = "New Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 6 or more characters" value = "<?php echo $npwd; ?>" required autocomplete = "off" /><span> * </span><br /><br />

				<label for = "cpassword">Confirm Password</label><br />
				<input type = "password" id = "cpassword" name = "cpassword" placeholder = "Confirm Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Please re-enter the same password" value = "<?php echo $cpwd; ?>" required autocomplete = "off" /><span> * <?php echo $cpwderr; ?></span><br /><br />
				
				<section align = "center">
					<input type = "Submit" value = "Submit" /><br /><br />
				</section>

			</div>

		</fieldset>

	</form>

</div>
<?php include('footer.htm'); ?>
</body>
</html>