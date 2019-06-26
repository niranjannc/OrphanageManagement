<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Adopt Infants" />
<meta name = "keywords" content = "Adoption, Infants" />
<meta name = "viewport" content = "width=device-width, initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "form.css" />

<title>Adoption</title>

</head>

<body>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" onclick = "window.location.href = 'adoptionlist.php'">Adoption List</button></li>
<li><button type = "button" onclick = "window.location.href = 'parentslist.php'">Parents List</button></li>
<li><button type = "button" onclick = "window.location.href = 'parentform.php'">Add Parents</button></li>
</ul>
</div>

<h1>ADOPTION PROCEDURE</h1>

<?php
include('connect.php');

$pid = testData($_POST["pid"]);
$fname = testData($_POST["fname"]);
$mname = testData($_POST["mname"]);
$infid = testData($_POST["infid"]);
$iname = testData($_POST["iname"]);

if($fname == "") 
	$fname = "---";
if($mname == "")
	$mname = "---";

?>
<div class = "tabcontent">
	<form name = "form" method = "POST" action = "<?php echo htmlspecialchars("adoptionprocess.php"); ?>" >
		
		<input type = "hidden" name = "infid" value = "<?php echo $infid; ?>" />
		
		<label for = "name">Infant</label><br />
		<input type = "text" name = "name" value = "<?php echo ucwords($iname); ?>" disabled /><br /><br />
		
		<input type = "hidden" name = "pid" value = "<?php echo $pid; ?>" />
		
		<label for = "fname">Father Name</label><br />
		<input type = "text" name = "fname" value = "<?php echo ucwords($fname); ?>" disabled /><br /><br />
		
		<label for = "mname">Mother Name</label><br />
		<input type = "text" name = "mname" value = "<?php echo ucwords($mname); ?>" disabled /><br /><br />
		
		<?php
			try {
				$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
				$sql = "USE $dbname"; 
	
				$conn->beginTransaction();
				$conn->exec($sql);
				include('orgid.php');
				
				$sql = "SELECT empid, fname, lname
						FROM employee
						WHERE orgid='$orgid'
							AND designation = 'head'";
				
				$stmt = $conn->prepare($sql);
				$stmt->execute();
				$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
				$arr = $stmt->fetchAll();
	
				$val=$arr[0];
				$empid = $val["empid"];
				$ename = $val["fname"].' '.$val["lname"];
				
				$conn->commit();
				$conn=null;
			}
			catch(PDOException $e) {
				$conn->rollback();
				echo $e->getMessage();
			}
		?>
		
		<input type = "hidden" name = "empid" value = "<?php echo $empid; ?>" />
		
		<label for = "ename">Approval Head</label><br />
		<input type = "text" name = "ename" value = "<?php echo ucwords($ename); ?>" disabled /><br /><br />
		<label for = "adoptdate">Adoption Date</label><br />
		<input type = "date" id = "adoptdate" name = "adoptdate" required />
		<span id = "err"></span><br /><br />
		
		<input type = "button" value = "Submit" class = "submit" onclick = "check()"/>
	</form>
</div>
<?php include('footer.htm'); ?>
<script>
	function check() {
		var status = false;
		if(document.getElementById('adoptdate').value == "") {
			document.getElementById('err').innerHTML = "<br/>Please fill the date field";
		}
		else {
			status = confirm('Approved by head?\nAre you sure you want to proceed?');
			//console.log(status);
		}
		if(status === true) {
			window.form.submit();
		}
	}
</script>
</body>
</html>