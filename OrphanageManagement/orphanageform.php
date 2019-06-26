<?php
session_start();
include('adminlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Add Orphanage to Trust" />
<meta name = "keywords" content = "Orphanage Form, Add Orphanages" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<title>Orphanage Form</title>

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "form.css" />

</head>

<body>

<?php
$name=$addlocation=$addcity=$adddistrict=$addstate=$phone=$email=$estyear=$trust=$status="";

if( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	include('connect.php');
	
	$name = testData($_POST["name"]);
	$addlocation = testData($_POST["addlocation"]);
	$addcity = testData($_POST["addcity"]);
	$adddistrict = testData($_POST["adddistrict"]);
	$addstate = testData($_POST["addstate"]);
	$phone = testData($_POST["phone"]);
	$email = testData($_POST["email"]);
	$estyear = testData($_POST["estyear"]);
	$trust = testData($_POST["trust"]);
	
	try {
		$conn = new PDO("mysql:host = $server; dbname = $dbname", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		$conn->beginTransaction();
		
		$sql = "USE $dbname";
		$conn->exec($sql);
		
		$sql1 = "SELECT count(*) as count from orphanage";
		
		$stmt = $conn->prepare($sql1);
		$stmt->execute();
		
		$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$arr = $stmt->fetchAll();
		
		//print_r ($arr);
		
		foreach($arr as $val) {
			$count = $val["count"];
			$count+=1;
			$orgid = "org".$count;
		}
		//echo $orgid;
		
		$sql2 = "Insert into orphanage values('$orgid', '$name', '$addlocation', '$addcity', '$adddistrict', '$addstate', '$phone',  '$email', '$estyear', '$trust', null)";
		
		$conn->exec($sql2);

		$status = true;
		
		$conn->commit();
		$conn =  null;
		if($status === true) {
			echo "<script>alert(\"Next Step : Orphanage Head Details\");</script>";
			
			$_SESSION["orpform"] = 1;
			$_SESSION["orgid"] = $orgid;
			header( 'Location: headform.php' );
			exit();
		}
	}
	catch(PDOException $e) {
		$conn->rollback();
		echo $e->getMessage();
	}
}
?>

<div>
	<?php
	include('adminlink.htm');
	?>
</div>

<div class="nestedlinks" style = "height : 1000px;" >
	<ul>
	<li><button type = "button" onclick = "window.location.href = 'orphanagelist.php'">Orphanage List</button></li>
	<li><button type = "button" >Add Orphanage</button></li>
	</ul>
</div>

<div class = "tabcontents" >

	<h1>ORPHANAGE DETAILS FORM</h1>

	<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >

		<span> *required </span><br /><br />

		<label for = "name">Name</label><br />
		<input type = "text" maxlength = "60" name = "name" id = "name" placeholder = "Orphanage Name" value = "<?php echo $name; ?>" required autocomplete = "off" />
		<span> * </span><br /><br />

		<label for = "address">Address</label><br />

		<input type = "text" id = "address" name = "addlocation" placeholder = "Location" value = "<?php echo $addlocation; ?>" maxlength = "45" required autocomplete = "off" /><span> * </span><br />

		<input type = "text" id = "address" name = "addcity" placeholder = "City" value = "<?php echo $addcity; ?>" maxlength = "25" style = "width : 31%" required autocomplete = "off" /><span> * </span>

		<input type = "text" id = "address" name = "adddistrict" placeholder = "District" value = "<?php echo $adddistrict?>" maxlength = "20" style = "width : 31%" required autocomplete = "off" /><span> * </span><br />

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

		<label for = "phone">Phone No.</label><br />
		<input type = "text" id = "phone" name = "phone" placeholder = "Phone no." value = "<?php echo $phone; ?>" maxlength = "10" pattern = "[0-9]{10}" title = "Proper mobile no. format" required autocomplete = "off" /><span> * </span><br /><br />

		<label for = "email">Email </label><br />
		<input type = "email" id = "email" name = "email" placeholder = "Email Id" value = "<?php echo $email ?>" maxlength = "30" required autocomplete = "off"/><span> * </span><br /><br />

		<label for = "year" >Year of Establishment</label><br />
		<input type = "month" id = "year" name = "estyear" placeholder = "Established Year" value = "<?php echo $estyear; ?>" required autocomplete = "off" /><span> * </span><br /><br />

		<label for = "trust" >Trust</label><br />
		<input type = "text" id = "trust" name = "trust" placeholder = "Trust Name" value = "<?php echo $trust; ?>" required autocomplete = "off" /><span> * </span><br /><br />

		<input type = "submit" value = "Submit" />


	</form>

</div>
<?php include('footer.htm'); ?>
</body>
</html>