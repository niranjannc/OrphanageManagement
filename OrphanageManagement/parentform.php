<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Parent Form" />
<meta name = "keywords" content = "Parent Form" />
<meta name = "viewport" content = "width=device-width, initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "form.css" />

<title>Parent Form</title>

<script>
	
</script>

</head>

<body>

<?php
$fname=$mname=$fbdate=$mbdate=$addlocation=$addcity=$adddistrict=$addstate=$phone=$photo=$photoerr=$agereq=$genreq=$status="";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	include('connect.php');
	
	$fname = testData( $_POST["fname"] );
	$mname = testData( $_POST["mname"] );
	$fbdate = testData( $_POST["fbdate"] );
	$mbdate = testData( $_POST["mbdate"] );
	$phone = testData( $_POST["phone"] );
	$addlocation = testData( $_POST["addlocation"] );
	$addcity = testData( $_POST["addcity"] );
	$adddistrict = testData( $_POST["adddistrict"] );
	$addstate = testData( $_POST["addstate"] );
	$agereq = testData($_POST["agereq"]);
	$genreq = testData( $_POST["genreq"] );
	
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
		
			$sql = "SELECT count(*) as count from parents";
		
			$stmt = $conn->prepare($sql);
			$stmt->execute();
		
			$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$arr = $stmt->fetchAll();
		
			//print_r ($arr);
		
			foreach($arr as $val) {
				$count = $val["count"];
				$count+=1;
				$pid = "prn".$count;
			}
			
			include('orgid.php');
			
			$sql = "insert into parents values('$orgid','$pid', '$fname', '$mname', '$fbdate', '$mbdate', '$addlocation', '$addcity', '$adddistrict', '$addstate', '$phone', '$agereq', '$genreq','$photo')";
			
			$conn->exec($sql);
			
			$status = true;
		
			$conn->commit();
			$conn =  null;
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
<li><button type = "button" onclick = "window.location.href = 'adoptionlist.php'">Adoption List</button></li>
<li><button type = "button" onclick = "window.location.href = 'parentslist.php'">Parents List</button></li>
<li><button type = "button" >Add Parents</button></li>
</ul>
</div>

<h1 name='h1'>FORM TO ADD PARENTS</h1>


<form name = "form" method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype = "multipart/form-data" >
	
	<div class = "formcontents">
		
		<label for = "fname" >Father Name</label><br />
		<input type = "text" id = "fname" name = "fname" placeholder = "Father" value = "<?php echo $fname; ?>" autocomplete = "off" />
		<span id="fnameerr" class="err"></span><br /><br />
		
		<label for = "mname" >Mother Name</label><br />
		<input type = "text" id = "mname" name = "mname" placeholder = "Mother" value = "<?php echo $mname; ?>" autocomplete = "off" />
		<span id="mnameerr" class="err"></span><br /><br />
		
		<label for = "fbdate">Father's Date of Birth</label><br />
		<input type = "date" id = "fbdate" name = "fbdate" value = "<?php echo $fbdate; ?>" /><span id="fbdateerr" class="err"></span><br /><br />
		
		<label for = "mbdate">Mother's Date of Birth</label><br />
		<input type = "date" id = "mbdate" name = "mbdate" value = "<?php echo $mbdate; ?>" /><span id="mbdateerr" class="err"></span><br /><br />
		
		<label for = "phone">Phone No.</label><br />
		<input type = "text" id = "phone" name = "phone" placeholder = "Phone no." value = "<?php echo $phone; ?>" maxlength = "10" pattern = "[0-9]{10}" title = "Proper mobile no. format" required autocomplete = "off" />
		<span id="phoneerr" class="err"> * </span><br /><br />
		
	</div>
	
	<div class = "formcontents">
	
		<label for = "address">Address</label><br />

		<input type = "text" id = "addlocation" name = "addlocation" placeholder = "Location" value = "<?php echo $addlocation; ?>" maxlength = "45" required autocomplete = "off" />
		<span id="addlocationerr" class="err"> * </span><br />

		<input type = "text" id = "addcity" name = "addcity" placeholder = "City" value = "<?php echo $addcity; ?>" maxlength = "25" style = "width : 28.5%" required autocomplete = "off" /><span class="err"> * </span>

		<input type = "text" id = "adddistrict" name = "adddistrict" placeholder = "District" value = "<?php echo $adddistrict?>" maxlength = "20" style = "width : 28.5%" required autocomplete = "off" /><span class="err"> * </span>
		<span style="padding-right:48px" id="addcityerr" class="err">
		</span><span  id="adddistricterr" class="err"></span><br /><br />

		<select id = "addstate" name = "addstate" required > 
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
		</select><span id="addstateerr" class="err"> * </span><br /><br /><br />
		
		<label for = "agereq">Age Requirement</label><br />
		<select id="agereq" name="agereq" required />
			<option value="" >--Select Range--</option>
			<option value="1" <?php if(isset($agereq) && $agereq=="1") echo selected; ?>>0-2</option>
			<option value="3" <?php if(isset($agereq) && $agereq=="3") echo selected; ?>>2-4</option>
			<option value="5" <?php if(isset($agereq) && $agereq=="5") echo selected; ?>>4-6</option>
			<option value="7" <?php if(isset($agereq) && $agereq=="7") echo selected; ?>>6-8</option>
			<option value="9" <?php if(isset($agereq) && $agereq=="9") echo selected; ?>>8-10</option>
			<option value="11" <?php if(isset($agereq) && $agereq=="11") echo selected; ?>>10-12</option>
			<option value="13" <?php if(isset($agereq) && $agereq=="13") echo selected; ?>>12-14</option>
			<option value="15" <?php if(isset($agereq) && $agereq=="15") echo selected; ?>>14-16</option>
			<option value="17" <?php if(isset($agereq) && $agereq=="17") echo selected; ?>>16-18</option>
		</select><span id="agereqerr" class="err"> * </span><br /><br />
		
		<label for = "genreq">Gender Requirement</label><span> * </span><br />
		<input type = "radio" id = "genm" name = "genreq" value = "m" <?php if(isset($genreq) && $genreq=="m") echo "checked"; ?> required /><label>Male</label>
		<input type = "radio" id = "genf" name = "genreq" value = "f" <?php if(isset($genreq) && $genreq=="f") echo "checked"; ?> required /><label id="labelf">Female</label>
		<input type = "radio" id = "geno" name = "genreq" value = "o" <?php if(isset($genreq) && $genreq=="o") echo "checked"; ?> required /><label>Others</label>
		<span id="genreqerr" class="err"></span><br /><br />
		
		<label for = "photo" >Photo</label><br /><br />
		<input type = "file" name = "photo" id = "photo" style = "height : 45px; border-radius : 0px;" /><span><?php echo $photoerr; ?></span><br /><br /><br />
		
	</div>
	
	<center>
	<input type = "button" class="submit" value = "Submit" onclick="validate()"/>
	</center>
		
</form>

<script>
	function validate() {
		var status = true;

		var err = document.getElementsByClassName('err');
		for(var i=0; i<err.length; i++) {
			err[i].innerHTML = "";
		}
		
		//console.log(document.getElementById('fname').value);
		if(document.getElementById('fname').value=="" && document.getElementById('mname').value=="") {
			document.getElementById('fnameerr').innerHTML = "<br />Please fill out the name fields. (Only one if a single parent)";
			document.getElementById('mnameerr').innerHTML = "<br />Please fill out the name fields. (Only one if a single parent)";
			status = false;
		}
		
		if(document.getElementById('fname').value!="" && document.getElementById('fbdate').value=="") {
			document.getElementById('fbdateerr').innerHTML = "<br />Please fill in the DOB field.";
			status = false;
		}
		
		if(document.getElementById('mname').value!="" && document.getElementById('mbdate').value=="") {
			document.getElementById('mbdateerr').innerHTML = "<br />Please fill in the DOB field.";
			status = false;
		}
		
		if(document.getElementById('mname').value=="" && document.getElementById('mbdate').value!="") {
			document.getElementById('mnameerr').innerHTML = "<br />Please fill in Name field";
			status = false;
		}
		
		if(document.getElementById('fname').value=="" && document.getElementById('fbdate').value!="") {
			document.getElementById('fnameerr').innerHTML = "<br />Please fill in Name field";
			status = false;
		}
		
		if(document.getElementById('phone').value=="") {
			document.getElementById('phoneerr').innerHTML = "<br />Phone Field is Empty";
			status = false;
		}
		else if(!(/[0-9]{10}/.test(document.getElementById('phone').value))) {
			document.getElementById('phoneerr').innerHTML = "<br />Please Enter a proper format";
			status = false;
		}
		
		if(document.getElementById('addlocation').value=="") {
			document.getElementById('addlocationerr').innerHTML = "<br />Location Field is Empty";
			status = false;
		}
		
		if(document.getElementById('addcity').value=="") {
			document.getElementById('addcityerr').innerHTML = "<br />City Field is Empty";
			status = false;
		}
		
		if(document.getElementById('adddistrict').value=="") {
			document.getElementById('adddistricterr').innerHTML = "District Field is Empty";
			status = false;
		}
		
		if(document.getElementById('addstate').value=="") {
			document.getElementById('addstateerr').innerHTML = "<br />State Field is Empty";
			status = false;
		}
		
		if(document.getElementById('agereq').value=="") {
			document.getElementById('agereqerr').innerHTML = "<br />Age requirement Field is Empty";
			status = false;
		}
		
		if(document.getElementById('genf').checked==false && document.getElementById('genm').checked==false && document.getElementById('geno').checked==false) {
			document.getElementById('genreqerr').innerHTML = "<br />Gender requirement Field is Empty";
			status = false;
		}
		
		if(document.getElementById('fname').value!="" && document.getElementById('mname').value=="" && document.getElementById('mbdate').value=="" && document.getElementById('genf').checked==true) {
			//console.log(document.getElementById('genm').checked);
			//console.log(document.getElementById('genf').checked);
			//console.log(document.getElementById('geno').checked);
			document.getElementById('genreqerr').innerHTML="<br />A Male parent cannot adopt a Female child";
			status = false;
		}
		
		if(document.getElementById('agereq').value!="") {
			var fbdate = document.getElementById('fbdate').value;
			var mbdate = document.getElementById('mbdate').value;
			if(mbdate == "") {
				mbdate = fbdate;
			}
			if(fbdate == "") {
				fbdate = mbdate;
			}
			fbdate = new Date(fbdate);
			mbdate = new Date(mbdate);
			
			var fage = calcage(fbdate);
			var mage = calcage(mbdate);
			//console.log(fage);
			
			var totage = fage+mage;
			//console.log(document.getElementById('agereq').value);
			var childage = document.getElementById('agereq').value;
			
			if(!((totage <= 90 && childage<=4) || (totage<=100 && childage<=8 && childage>4) || (totage<110 && childage>8 && childage<=18))) {
				status = false;
				document.getElementById('agereqerr').innerHTML = "<br />Cannot adopt child of this age range";
			}
			
			var minage = minagecalc(fage, mage);
			//console.log((minage-childage));
			if((minage-childage)<25) {
				status = false;
				document.getElementById('agereqerr').innerHTML = "<br />Age difference should be more than 25 years.";
			}
		}
		
		if(status === true) {
			document.form.submit();
		}
	}
	
	function calcage(date) {
		var curdate = new Date();
			
		var yy = curdate.getYear()-date.getYear();
		var mm = curdate.getMonth()-date.getMonth();
		if(mm < 0) {
			yy--;
		}
		var dd = curdate.getDate()-date.getDate();
		if(dd < 0) {
			mm--;
		}
		if(mm < 0) {
			yy--;
		}
		return yy;
	}
	
	function minagecalc(date1, date2) {
		if(date1 <= date2)
			return date1;
		else
			return date2;
	}
</script>

<?php
if($status === true) {
	echo '<script>
			alert("Added Parents Sucessfully");
			window.location.href="parentslist.php";
		</script>';
}
?>
<?php include('footer.htm'); ?>
</body>
</html>