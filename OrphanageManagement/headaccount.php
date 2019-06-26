<?php
session_start();
include('adminlogincheck.php');

if( $_SESSION["orpform"] != 2) {
	echo '<script>alert("Please fill in Orphanage Details");</script>';
	
	header('Location: orphanageform.php');
	exit();
}

?>

<!doctype html>

<html>

<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Registration Page" />
<meta name = "keywords" content = "Registration Page, Sign Up Page" />
<meta name = "viewport" content = "width=device-width, initial-scale = 1.0" />

<title>Registration Page for Head</title>

<link rel = "stylesheet" type = "text/css" href = "span_input.css" />
<link rel = "stylesheet" type = "text/css" href = "fieldset.css" />

</head>

<body>
<?php
include('connect.php');

//setting variables to null
$uname=$pwd=$cpwd=$status="";
$unameerr=$cpwderr="";

//if form is submitted
if($_SERVER["REQUEST_METHOD"]=="POST") {
	
	$uname = testData($_POST["username"]);
	$pwd = testData($_POST["password"]);
	$cpwd = testData($_POST["cpassword"]);
	$role = "head";
	$empid = $_SESSION["empid"];

	if($pwd != $cpwd) {
		$cpwderr="Incorrect Password. Please Retype Password same as above.";
	 }

	if($cpwderr=="") {

		try {
			$conn = new PDO("mysql:host = $server;dbname = $dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
			//begin transaction
			$conn->beginTransaction();

			$sql = "USE $dbname";
			$conn->exec($sql);

			$stmt=$conn->prepare("SELECT username FROM login");
			$stmt->execute();
		
			$result=$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$arr = $stmt->fetchAll();

			foreach ($arr as $value) {
				if( $value["username"] == $uname ) {
					$unameerr = "Username already Exists";
					break;
				 }
			 }
		
			if( $unameerr=="" ) {
				$sql = "INSERT INTO login VALUES( '$uname', '$pwd', '$role', '$empid')";
				$conn->exec($sql);
				echo '<script>alert("Successfully Created");</script>';
				$status = true;
			 }
			
			$conn->commit();
			$conn = null;		
	 	 }
		catch (PDOException $e) {
			$conn->rollback();
			$unameerr = "Username Already Exists";
		 }
		
 	 }
 }

?>

<h2 align = "center">HEAD REGISTRATION PAGE</h2>

<div align = "center" style = "margin : 20px;">

	<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >

		<fieldset>
			<legend>SIGN UP</legend>

			<div align = "left" id = "image">

				<span >* required</span><br /><br />

				<label for = "username">Username</label><br />
				<input type = "text" id = "username" name = "username" placeholder = "Username" value = "<?php echo $uname; ?>" required autocomplete = "off" /><span> * <?php echo $unameerr; ?></span><br /><br />

				<label for = "password">Password</label><br />
				<input type = "password" id = "password" name = "password" placeholder = "Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 6 or more characters" value = "<?php echo $pwd; ?>" required autocomplete = "off" /><span> * </span><br /><br />

				<label for = "cpassword">Confirm Password</label><br />
				<input type = "password" id = "cpassword" name = "cpassword" placeholder = "Confirm Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Please re-enter the same password" value = "<?php echo $cpwd; ?>" required autocomplete = "off" /><span> * <?php echo $cpwderr; ?></span><br /><br />
				
				<section align = "center">
					<input type = "Submit" value = "Submit" /><br /><br />
				</section>

			</div>

		</fieldset>

	</form>

</div>

<?php
if( $status === true) {
	unset($_SESSION["orpform"]);
	unset($_SESSION["empid"]);
	unset($_SESSION["orgid"]);
	
	header('Location: orphanageform.php');
	exit();
}
?>
<?php include('footer.htm'); ?>
</body>
</html>