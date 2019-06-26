<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Donation form under Orphanage" />
<meta name = "keywords" content = "Donation form" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = "stylesheet" type = "text/css" href = "form.css" />
<link rel = 'stylesheet' type = "text/css" href = "links.css" />

<title>Donation Form</title>

<style>
#back {
	width : 5%;
	background-color : blue;
	color : white;
	border-radius : 4px;
	padding : 10px;
	font-size : 110%;
	cursor : pointer;
	/*margin-left : 50px;*/
}
</style>

</head>

<body>
<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" onclick = "window.location.href = 'donor_list.php'">Donors & Trustees List</button></li>
<li><button type = "button" onclick = "window.location.href = 'donor_form.php'">Add Donors/Trustees</button></li>
<li><button type = "button" onclick = "window.location.href = 'donation_list.php'">Donation List</button></li>
<li><button type = "button" onclick = "window.location.href = 'sponser_list.php'">Sponsered List</button></li>
</ul>
</div>

<?php
$did = $_POST["did"];
$name = $_POST["name"];
$amount=$service=$purpose=$ddate=$mode="";

?>
<!--<button id = "back" type = "button" onclick = "window.location.href = 'donorlist.php'">Back</button><br />-->
<h1>DONATION FORM</h1>

<!--<div style = "float : left; width : 30%; height : 700px;" >
</div>-->

<div class = "tabcontent" style = " float : left; width : 65%;">
	<form name = "donationform" method = "POST" action = "<?php echo htmlspecialchars("donation_formprocess.php"); ?>" >

		<label for = "name">Name </label><br />
		<input type = "text" id = "name" name = "name" value = "<?php echo ucwords($name); ?>" disabled /><br /><br />
		
		<input type = "hidden" name = "did" value = "<?php echo $did; ?>" />
		
		<label for = "amount">Amount </label><br />
		<input type = "number" id = "amount" name = "amount" placeholder = "Amount of Donation" value = "<?php echo $amount; ?>" autocomplete = "off" /><span id="amounterr">  </span><br /><br />
		
		<label for = "mode">Payment Mode </label><br />
		<select id = "mode" name = "mode" >
			<option value = "" >--Select--</option>
			<option value = "cash" <?php if((isset($mode)) && $mode == "cash") echo "selected"; ?> >Cash</option>
			<option value = "cheque" <?php if((isset($mode)) && $mode == "cheque") echo "selected"; ?> >Cheque</option>
			<option value = "rtgs" <?php if((isset($mode)) && $mode == "rtgs") echo "selected"; ?> >RTGS</option>
			<option value = "dd" <?php if((isset($mode)) && $mode == "dd") echo "selected"; ?> >Demand Draft(DD)</option>
			<option value = "card" <?php if((isset($mode)) && $mode == "card") echo "selected"; ?> >Credit/Debit Card</option>
		</select><span id="modeerr">  </span><br /><br />
		
		<label for = "service">Service </label><br />
		<input type = "text" id = "service" name = "service" placeholder = "Service Offered" value = "<?php echo $service; ?>" maxlength = "22" autocomplete = "off" /><span id="serviceerr"></span><br /><br />
		
		<label for = "purpose">Purpose </label><br />
		<input type = "text" id = "purpose" name = "purpose" placeholder = "Purpose of Donation" value = "<?php echo $purpose; ?>" maxlength = "12" autocomplete = "off" /><span id="purposeerr">  </span><br /><br />
		
		<label for = "ddate">Date </label><br />
		<input type = "date" id = "ddate" name = "ddate" placeholder = "Donation date" value = "<?php echo $ddate; ?>" maxlength = "12" autocomplete = "off" required /><br /><br />
		
		<section align = "margin-left : 400px" >
			<input type = "button" name = "donation" class = "submit" value = "Submit" onclick = "validate()"/>
		</section>
	</form>
</div>

<script>
function validate() {
	var status = true;
	document.getElementById("modeerr").innerHTML = "";
	document.getElementById("amounterr").innerHTML = "";
	document.getElementById("serviceerr").innerHTML = "";
	
	if(document.getElementById("amount").value != "") {
			if(document.getElementById("mode").value == "") {
				document.getElementById("modeerr").innerHTML = "Please fill in the Mode of Payment";
				status = false;
			}
	}
	
	if(document.getElementById("mode").value != "") {
			if(document.getElementById("amount").value == "" ) {
				document.getElementById("amounterr").innerHTML = "Please fill in the Payment Amount";
				status = false;
			}
	}
	
	if((document.getElementById("amount").value)== "" && (document.getElementById("mode").value)== "") {
		if(document.getElementById("service").value == "") {
			document.getElementById("serviceerr").innerHTML = "Please specify Amount or Mode or Service ";
			status = false;
		}
	}
	
	if(status === true) {
		document.donationform.submit();
	}
	/*if(document.getElementById("modeerr").innerHTML == "" && document.getElementById("amounterr").innerHTML == "" && document.getElementById("serviceerr").innerHTML == "") {
		document.donationform.submit();
	}*/
}
</script>
<?php include('footer.htm'); ?>
</body>
</html>