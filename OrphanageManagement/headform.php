<?php
session_start();
include('adminlogincheck.php');

if( $_SESSION["orpform"] != 1) {
	echo '<script>alert("Please fill in Orphanage Details");</script>';
	
	header('Location: orphanageform.php');
	exit();
}

?>

<!doctype html>

<html>
<head>

<meta charset = "utf-8" />
<meta name = "description" content = "Orphanage Head Form" />
<meta name = "keywords" content = "Head form" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<title>Head Form</title>

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "form.css" />

</head>

<body>

<?php
$fname=$lname=$gender=$bdate=$addlocation=$addcity=$adddistrict=$addstate=$phone=$salary=$joindate=$photo=$photoerr="";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	include('connect.php');
	
	$fname = testData( $_POST["fname"] );
	$lname = testData( $_POST["lname"] );
	$gender = testData( $_POST["gender"] );
	$bdate = testData( $_POST["bdate"] );
	$addlocation = testData( $_POST["addlocation"] );
	$addcity = testData( $_POST["addcity"] );
	$adddistrict = testData( $_POST["adddistrict"] );
	$addstate = testData( $_POST["addstate"] );
	$phone = testData( $_POST["phone"] );
	$salary = testData( $_POST["salary"] );
	$joindate = testData( $_POST["joindate"] );
	
	$designation = "head";
	$orgid = $_SESSION["orgid"];
	
	if(($_FILES["photo"]["name"])!="" ) {
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
		
			$sql1 = "SELECT count(*) as count from employee";
		
			$stmt = $conn->prepare($sql1);
			$stmt->execute();
		
			$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$arr = $stmt->fetchAll();
		
			//print_r ($arr);
		
			foreach($arr as $val) {
				$count = $val["count"];
				$count+=1;
				$empid = "emp".$count;
			}
			//echo $empid;
			//echo $bdate;
		
			$sql2 = "insert into employee values('$orgid','$empid', '$fname', '$lname', '$gender', '$bdate', '$addlocation', '$addcity', '$adddistrict', '$addstate', '$phone', '$designation', '$salary', '$joindate', 'n', null, '$photo')";
			
			$sql3 = "update orphanage set head = '$empid' where orgid = '$orgid'";
		
			$conn->exec($sql2);
			$conn->exec($sql3);

			$status = true;
		
			$conn->commit();
			$conn =  null;
		
			if($status === true) {
				echo "<script>alert(\"Next Step : Head Account Creation\");</script>";
	
				//$_SESSION["orgid"] = $orgid;
				$_SESSION["orpform"] = 2;
				$_SESSION["empid"] = $empid;
				
				header( 'Location: headaccount.php' );
				exit();
			}
		}
		catch(PDOException $e) {
			$conn->rollback();
			echo $e->getMessage();
		}
	}
	
}
?>

<h1 align = "center">FORM TO ASSIGN HEAD TO ORPHANAGE</h1>

<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype = "multipart/form-data" >
	
	<div class = "formcontents" style = "margin-left : 150px; width : 40%">	

		<label for = "name" >Name</label><br />
		<input type = "text" id = "name" name = "fname" placeholder = "First Name" value = "<?php echo $fname; ?>" style = "width : 28.5%" required autocomplete = "off" /><span> * </span>
		<input type = "text" id = "name" name = "lname" placeholder = "Last Name" value = "<?php echo $lname; ?>" style = "width : 28.5%" autocomplete = "off" /><br /> <br />

		<label for = "gender" >Gender</label><span> * </span><br />
		<input type = "radio" id = "gender" name = "gender" value = "male" <?php if (isset($gender) && $gender == "male") echo "checked" ?> /><label>Male			</label>		
		<input type = "radio" id = "gender" name = "gender" value = "female" <?php if (isset($gender) && $gender == "female") echo "checked" ?> /><label>Female				</label> 
		<input type = "radio" id = "gender" name = "gender" value = "other" <?php if (isset($gender) && $gender == "other") echo "checked" ?> /><label>Other </label><br /><br />

		<label for = "bdate">Date of Birth</label><br />
		<input type = "date" id = "bdate" name = "bdate" value = "<?php echo $bdate; ?>" /><br /><br />

		<label for = "phone">Phone No.</label><br />
		<input type = "text" id = "phone" name = "phone" placeholder = "Phone no." value = "<?php echo $phone; ?>" maxlength = "10" pattern = "[0-9]{10}" title = "Proper mobile no. format" required autocomplete = "off" /><span> * </span><br /><br />
		
		<label for = "salary">Salary</label><br />
		<input type = "number" id = "salary" name = "salary" placeholder = "Salary" value = "<?php echo $salary; ?>"  required autocomplete = "off" /><span> * </span><br /> <br />
		
	</div>
	
	<div class = "formcontents">
	
		<label for = "address">Address</label><br />

		<input type = "text" id = "address" name = "addlocation" placeholder = "Location" value = "<?php echo $addlocation; ?>" maxlength = "45" required autocomplete = "off" /><span> * </span><br />

		<input type = "text" id = "address" name = "addcity" placeholder = "City" value = "<?php echo $addcity; ?>" maxlength = "25" style = "width : 28.5%" required autocomplete = "off" /><span> * </span>

		<input type = "text" id = "address" name = "adddistrict" placeholder = "District" value = "<?php echo $adddistrict?>" maxlength = "20" style = "width : 28.5%" required autocomplete = "off" /><span> * </span><br />

		<select id = "address" name = "addstate" required > 
			<option value = "" >--Select State--</option>
			<option value = "Andhra Pradesh" <?php if (isset($addstate) && $addstate == "Andhra Pradesh" ) echo "selected"; ?> >Andhra Pradesh</option>
			<option value = "Arunachal Pradesh" <?php if (isset($addstate) && $addstate == "Arunachal Pradesh" ) echo "selected"; ?> >Arunachal Pradesh</option>
			<option value = "Assam" <?php if (isset($addstate) && $addstate == "Assam" ) echo "selected"; ?> >Assam</option>
			<option value = "Bihar" <?php if (isset($addstate) && $addstate == "Bihar" ) echo "selected"; ?> >Bihar</option>
			<option value = "Chatisgarh" <?php if (isset($addstate) && $addstate == "Chatisgarh" ) echo "selected"; ?> >Chatisgarh</option>
			<option value = "Goa" <?php if (isset($addstate) && $addstate == "Goa" ) echo "selected"; ?> >Goa</option>
			<option value = "Gujarat" <?php if (isset($addstate) && $addstate == "Gujarat" ) echo "selected"; ?> >Gujarat</option>
			<option value = "Haryana" <?php if (isset($addstate) && $addstate == "Haryana" ) echo "selected"; ?> >Haryana</option>
			<option value = "Himachal Pradesh" <?php if (isset($addstate) && $addstate == "Himachal Pradesh" ) echo "selected"; ?> >Himachal Pradesh</option>
			<option value = "Jammu Kashmir" <?php if (isset($addstate) && $addstate == "Jammu Kashmir" ) echo "selected"; ?> >Jammu Kashmir</option>
			<option value = "Jharkand" <?php if (isset($addstate) && $addstate == "Jharkand" ) echo "selected"; ?> >Jharkand</option>
			<option value = "Karnataka" <?php if (isset($addstate) && $addstate == "Karnataka" ) echo "selected"; ?> >Karnataka</option>
			<option value = "Kerala" <?php if (isset($addstate) && $addstate == "Kerala" ) echo "selected"; ?> >Kerala</option>
			<option value = "Madhya Pradesh" <?php if (isset($addstate) && $addstate == "Madhya Pradesh" ) echo "selected"; ?> >Madhya Pradesh</option>
			<option value = "Maharastra" <?php if (isset($addstate) && $addstate == "Maharastra" ) echo "selected"; ?> >Maharastra</option>
			<option value = "Manipur" <?php if (isset($addstate) && $addstate == "Manipur" ) echo "selected"; ?> >Manipur</option>
			<option value = "Meghalaya" <?php if (isset($addstate) && $addstate == "Meghalaya" ) echo "selected"; ?> >Meghalaya</option>
			<option value = "Mizoram" <?php if (isset($addstate) && $addstate == "Mizoram" ) echo "selected"; ?> >Mizoram</option>
			<option value = "Nagaland" <?php if (isset($addstate) && $addstate == "Nagaland" ) echo "selected"; ?> >Nagaland</option>
			<option value = "Orissa" <?php if (isset($addstate) && $addstate == "Orissa" ) echo "selected"; ?> >Orissa</option>
			<option value = "Punjab" <?php if (isset($addstate) && $addstate == "Punjab" ) echo "selected"; ?> >Punjab</option>
			<option value = "Rajasthan" <?php if (isset($addstate) && $addstate == "Rajasthan" ) echo "selected"; ?> >Rajasthan</option>
			<option value = "Sikkim" <?php if (isset($addstate) && $addstate == "Sikkim" ) echo "selected"; ?> >Sikkim</option>
			<option value = "Tamilnadu" <?php if (isset($addstate) && $addstate == "Tamilnadu" ) echo "selected"; ?> >Tamilnadu</option>
			<option value = "Telangana" <?php if (isset($addstate) && $addstate == "Telangana" ) echo "selected"; ?> >Telangana</option>
			<option value = "Tripura" <?php if (isset($addstate) && $addstate == "Tripura" ) echo "selected"; ?> >Tripura</option>
			<option value = "Uttaranchal" <?php if (isset($addstate) && $addstate == "Uttaranchal" ) echo "selected"; ?> >Uttaranchal</option>
			<option value = "Uttar Pradesh" <?php if (isset($addstate) && $addstate == "Uttar Pradesh" ) echo "selected"; ?> >Uttar Pradesh</option>
			<option value = "West Bengal" <?php if (isset($addstate) && $addstate == "West Bengal" ) echo "selected"; ?> >West Bengal</option>
		</select><span> * </span><br /><br /><br />
		
		<label for = "joindate">Join Date</label><br />
		<input type = "date" id = "joindate" name = "joindate" placeholder = " Join Date" value = "<?php echo $joindate;?>" required /><span> * </span><br /><br />
		
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