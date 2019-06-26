<?php
session_start();
include('adminlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Orphanage Edit Form" />
<meta name = "keywords" content = "Edit Orphanage, Change Head" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = "stylesheet" type = "text/css" href = "form.css" />
<link rel = 'stylesheet' type = "text/css" href = "links.css" />

<title>Orphanage Edit Form</title>

<style>
h2 {
	margin : 30px;
	cursor : pointer;
	color : green;
}
</style>

<script>
	
</script>

</head>

<body>

<div>
<?php
include( 'adminlink.htm' );
$name = $_POST["oname"];
$orgid = $_POST["orgid"];
?>
</div>

<div class = "nestedlinks" style = "height : 820px;">
<ul>
<li><button type = "button" onclick = "window.location.href = 'orphanagelist.php'">Orphanage List</button></li>
<li><button type = "button" onclick = "window.location.href = 'orphanageform.php'">Add Orphanage</button></li>
</ul>
</div>

<h1>ORPHANAGE EDIT FORM</h1>


<h2 onclick = "display(0)" >CHANGE ADDRESS</h2>
<div class = "tab">
	<form method = "POST" action = "<?php echo htmlspecialchars("orphanageeditprocess.php") ?>" >
		<label for = "name">Name</label><br />
		<input type = "text" id = "name" name = "name" value = "<?php echo $name; ?>" disabled /><br /><br />
		
		<input type = "hidden" name = "orgid" value = "<?php echo $orgid ?>"/>
		
		<label for = "address">Address</label><br />

		<input type = "text" id = "address" name = "addlocation" placeholder = "Location" maxlength = "45" required autocomplete = "off" /><span> * </span><br />

		<input type = "text" id = "address" name = "addcity" placeholder = "City" maxlength = "25" style = "width : 31%" required autocomplete = "off" /><span> * </span>

		<input type = "text" id = "address" name = "adddistrict" placeholder = "District" maxlength = "20" style = "width : 31%" required autocomplete = "off" /><span> * </span><br />

		<select id = "address" name = "addstate" style = "width : 66.5%" required > 
			<option value = "" >--Select State--</option>
			<option value = "Andhra Pradesh" >Andhra Pradesh</option>
			<option value = "Arunachal Pradesh" >Arunachal Pradesh</option>
			<option value = "Assam" >Assam</option>
			<option value = "Bihar" >Bihar</option>
			<option value = "Chatisgarh">Chatisgarh</option>
			<option value = "Goa" >Goa</option>
			<option value = "Gujarat" >Gujarat</option>
			<option value = "Haryana" >Haryana</option>
			<option value = "Himachal Pradesh" >Himachal Pradesh</option>
			<option value = "Jammu Kashmir" >Jammu Kashmir</option>
			<option value = "Jharkand" >Jharkand</option>
			<option value = "Karnataka" >Karnataka</option>
			<option value = "Kerala" >Kerala</option>
			<option value = "Madhya Pradesh" >Madhya Pradesh</option>
			<option value = "Maharastra" >Maharastra</option>
			<option value = "Manipur" >Manipur</option>
			<option value = "Meghalaya" >Meghalaya</option>
			<option value = "Mizoram" >Mizoram</option>
			<option value = "Nagaland" >Nagaland</option>
			<option value = "Orissa" >Orissa</option>
			<option value = "Punjab" >Punjab</option>
			<option value = "Rajasthan" >Rajasthan</option>
			<option value = "Sikkim" >Sikkim</option>
			<option value = "Tamilnadu" >Tamilnadu</option>
			<option value = "Telangana" >Telangana</option>
			<option value = "Tripura" >Tripura</option>
			<option value = "Uttaranchal" >Uttaranchal</option>
			<option value = "Uttar Pradesh" >Uttar Pradesh</option>
			<option value = "West Bengal" >West Bengal</option>
		</select><span> * </span><br /><br /><br />
		
		<input type = "Submit" name = "changeaddress" value = "Submit" />

	</form>
</div>
<hr color = "grey" width = '65%' align = "left" />

<h2 onclick = "display(1)">CHANGE PHONE NUMBER</h2>
<div class = "tab">
	<form method = "POST" action = "<?php echo htmlspecialchars("orphanageeditprocess.php") ?>">
		
		<label for = "name">Name</label><br />
		<input type = "text" id = "name" name = "name" value = "<?php echo $name; ?>" disabled /><br /><br />
		
		<input type = "hidden" name = "orgid" value = "<?php echo $orgid ?>"/>
		
		<label for = "phone">Phone No.</label><br />
		<input type = "text" id = "phone" name = "phone" placeholder = "Phone no." maxlength = "10" pattern = "[0-9]{10}" title = "Proper mobile no. format" required autocomplete = "off" /><span> * </span><br /><br />

		<input type = "Submit" name = "changephone" value = "Submit" />

	</form>
</div>
<hr color = "grey" width = '65%' align = "left" />

<h2 onclick = "display(2)">CHANGE EMAIL</h2>
<div class = "tab">
	<form method = "POST" action = "<?php echo htmlspecialchars("orphanageeditprocess.php") ?>" >
	
		<label for = "name">Name</label><br />
		<input type = "text" id = "name" name = "name" value = "<?php echo $name; ?>" disabled /><br /><br />
		
		<input type = "hidden" name = "orgid" value = "<?php echo $orgid ?>"/>
		
		<label for = "email">Email </label><br />
		<input type = "email" id = "email" name = "email" placeholder = "Email Id" required autocomplete = "off"/><span> * </span><br /><br />
		
		<input type = "Submit" name = "changeemail" value = "Submit" />

	</form>
</div>
<hr color = "grey" width = '65%' align = "left" />

<h2 onclick = "display(3)">CHANGE HEAD</h2>
<div class = "tab">
	<form method = "POST" action = "<?php echo htmlspecialchars("orphanageeditprocess.php") ?>" >
		<label for = "name">Name</label><br />
		<input type = "text" id = "name" name = "name" value = "<?php echo $name; ?>" disabled /><br /><br />
		
		<input type = "hidden" name = "orgid" value = "<?php echo $orgid ?>"/>
		
		<label for = "head">Head</label><br />
		<select id = "head" name = "head" style = "width : 66.5%" required >
			<option >--Select Head--</option>
			<?php
			include('connect.php');
			
			try{
				$conn = new PDO("mysql:host = $server; dbname = $dbname", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			
				$sql = "USE $dbname";
				$sql1 = "SELECT empid, fname, lname 
						FROM employee
						WHERE orgid = '$orgid'";
				
				$conn->beginTransaction();
				$conn->exec($sql);
				
				$stmt = $conn->prepare($sql1);
				$stmt->execute();
				
				$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
				$arr = $stmt->fetchAll();
				
				foreach($arr as $val){
					echo '<option value = "'.$val["empid"].'" >'.$val["fname"].' '.$val["lname"].'</option>';
				}
			
				$conn->commit();
				$conn = null;
			}
			catch(PDOException $e){
				$conn->rollback();
				$e->getMessage();
			}
		?>
		</select><br /><br />
		
		<input type = "Submit" name = "changehead" value = "Submit" />

	</form>
</div>
<hr color = "grey" width = '65%' align = "left" />

<script>
	//document.getElementById("changeaddress").style.display = "none";
	var x = document.getElementsByClassName("tab");
	for ( var i=0; i<(x.length); i++) {
		x[i].style.display = "none";
	}
	
	function display(n) {
		var x = document.getElementsByClassName("tab");
		for ( var i=0; i<(x.length); i++) {
			x[i].style.display = "none";
		}
		x[n].style.display = "inline";
	}
	
</script>
<?php include('footer.htm'); ?>
</body>
</html>