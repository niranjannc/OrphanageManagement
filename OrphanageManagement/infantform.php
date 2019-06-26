<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "utf-8" />
<meta name = "description" content = "Infant Form" />
<meta name = "keywords" content = "Infant form" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<title>Infant Form</title>

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "form.css" />

</head>

<body>

<?php
$fname=$lname=$gender=$bdate=$disability=$joindate=$photo=$photoerr="";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	include('connect.php');
	
	$fname = testData( $_POST["fname"] );
	$lname = testData( $_POST["lname"] );
	$gender = testData( $_POST["gender"] );
	$bdate = testData( $_POST["bdate"] );
	$disability = testData($_POST["disability"]);
	$joindate = testData($_POST["joindate"]);
	$caretaker = testData($_POST["caretaker"]);
	
	if( !empty($_FILES["photo"]["name"]) ) {
		$phototype = $_FILES["photo"]["type"];
		
		if ( $phototype != "image/jpg" && $phototype != "image/jpeg" && $phototype != "image/png" && $phototype != "image/gif" ) {
			$photoerr = "Uploaded File is not image";
		}
	
		$photosize = $_FILES["photo"]["size"];
		if($photosize > 1000000) {
			$photoerr = "Image too Large(>1MB)";
		}
		
		$photo = addslashes($_FILES["photo"]["tmp_name"]);
		$photo = file_get_contents($photo);
		$photo = base64_encode($photo);
	}
	
	if($photoerr == "") {
		try {
			$conn = new PDO("mysql:host = $server; dbname = $dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
			$conn->beginTransaction();
		
			$sql = "USE $dbname";
			$conn->exec($sql);
			
			$sql = "SELECT count(*) as count from infant";
		
			$stmt = $conn->prepare($sql);
			$stmt->execute();
		
			$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$arr = $stmt->fetchAll();
			
			$val = $arr[0];
			
			$count = $val["count"];
			$count+=1;
			$infid = "inf".$count;
			
			include('orgid.php');
			
			$sql = "insert into infant values('$orgid','$infid', '$fname', '$lname', '$gender', '$bdate', '$disability', '$caretaker', '$joindate', 'n', null, '$photo')";
			
			$conn->exec($sql);
			
			$status = true;
		
			$conn->commit();
			$conn =  null;
		
			if($status === true) {
				echo "<script>
						document.location.href = 'infantform.php';
					</script>";
				
			}
		}
		catch(PDOException $e) {
			$conn->rollback();
			echo $e->getMessage();
		}
	}
	
}
?>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" onclick = "window.location.href = 'infantslist.php'">Infants List</button></li>
<li><button type = "button" >Add Infants</button></li>
<li><button type = "button" onclick = "window.location.href = 'requiresponser.php'">Require Sponsering</button></li>
<li><button type = "button" onclick = "window.location.href = 'leftinfantslist.php'">Left Infants List</ul>
</div>

<h1>FORM TO ADD INFANT</h1>

<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype = "multipart/form-data" >
	
	<div class = "formcontents" style = "height : 400px;">
		
		<label for = "name" >Name</label><br />
		<input type = "text" id = "name" name = "fname" placeholder = "First Name" value = "<?php echo $fname; ?>" style = "width : 28.5%" required autocomplete = "off" /><span> * </span>
		<input type = "text" id = "name" name = "lname" placeholder = "Last Name" value = "<?php echo $lname; ?>" style = "width : 28.5%" autocomplete = "off" /><br /> <br />

		<label for = "gender" >Gender</label><span> * </span><br />
		<input type = "radio" id = "gender" name = "gender" value = "male" <?php if (isset($gender) && $gender == "male") echo "checked" ?> /><label>Male			</label>		
		<input type = "radio" id = "gender" name = "gender" value = "female" <?php if (isset($gender) && $gender == "female") echo "checked" ?> /><label>Female				</label> 
		<input type = "radio" id = "gender" name = "gender" value = "other" <?php if (isset($gender) && $gender == "other") echo "checked" ?> /><label>Other </label><br /><br />

		<label for = "bdate">Date of Birth</label><br />
		<input type = "date" id = "bdate" name = "bdate" value = "<?php echo $bdate; ?>" /><span> * </span><br /><br />
		
		<label for = "disability">Disability</label><br />

		<select id = "disability" name = "disability" required > 
			<option value = "None" selected >None</option>
			<option value = "PH" <?php if (isset($disability) && $disability == "PH" ) echo "selected"; ?> >Physically Handicapped(PH)</option>
			<option value = "MD" <?php if (isset($disability) && $disability == "MD" ) echo "selected"; ?> >Mentally Disabled(MD)</option>
			<option value = "Both" <?php if (isset($disability) && $disability == "Both" ) echo "selected"; ?> >Both PH & MD</option>
		</select><br /><br /><br />
		
	</div>
	
	<div class = "formcontents" style = "height : 400px;">
	
		
		<label for = "joindate">Join Date</label><br />
		<input type = "date" id = "joindate" name = "joindate" value = "<?php echo $joindate; ?>" /><span> * </span><br /><br /><br />
		
		<label for = "caretaker" >Assign Caretaker</label><br />
		<select id = "caretaker" name = "caretaker" required>
			<option value = "">--Select--</option>
			<?php
				include('connect.php');
				
				try {
					$conn = new PDO("mysql:host = $server; dbname = $dbname",$username, $password);
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
					$conn->beginTransaction();
				
					$sql ="USE $dbname";
					$conn->exec($sql);
					
					include('orgid.php');
					
					$sql = "SELECT empid, fname, lname
							FROM employee
							WHERE orgid = '$orgid'
								AND hasleft='n'";
							
					$stmt = $conn->prepare($sql);
					$stmt->execute();
	
					$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
					$arr = $stmt->fetchAll();

					foreach($arr as $val) {
						echo '<option value = "'.$val["empid"].'">'.ucwords($val["fname"]).' '.ucwords($val["lname"]).'</option>';
					}
					
					$conn->commit();
					$conn = null;
				}
				catch(PDOException $e) {
					$conn->rollback();
					echo $e->getMessage();
				}
			?>
		</select><span> * </span><br /><br /><br />
		
		<label for = "photo" >Photo</label><br /><br />
		<input type = "file" name = "photo" id = "photo" style = "height : 45px; border-radius : 0px;" /><span><?php echo $photoerr; ?></span><br /><br /><br />
		
	</div>
	
	<center>
	<input type = "submit" value = "Submit" />
	</center>
		
</form>
<?php include('footer.htm'); ?>
</body>
</html>