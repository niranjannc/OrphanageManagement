<?php
session_start();
include('headlogincheck.php');

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

<title>Infant Edit Form</title>

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
include( 'headlink.htm' );
$name = $_POST["name"];
$infid = $_POST["infid"];
?>
</div>

<div class = "nestedlinks" style = "height : 700px;">
<ul>
<li><button type = "button" onclick = "window.location.href = 'infantslist.php'">Infants List</button></li>
<li><button type = "button" onclick = "window.location.href = 'infantform.php'">Add Infants</button></li>
<li><button type = "button" onclick = "window.location.href = 'requiresponser.php'">Require Sponsering</button></li>
<li><button type = "button" onclick = "window.location.href = 'leftinfantslist.php'">Left Infants List</button></li>
</ul>
</div>

<h1>INFANT EDIT FORM</h1>


<h2 onclick = "display(0)" >ASSIGN CARETAKER</h2>
<div class = "tab">
	<form method = "POST" action = "<?php echo htmlspecialchars("infanteditprocess.php") ?>" >
		
		<label for = "name">Name</label><br />
		<input type = "text" id = "name" name = "name" value = "<?php echo $name; ?>" disabled /><br /><br />
		
		<input type = "hidden" name = "infid" value = "<?php echo $infid ?>"/>
		
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
								AND hasleft = 'n'";
					
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
		
		<input type = "Submit" name = "assigncaretaker" value = "Submit" />

	</form>
</div>
<hr color = "grey" width = '65%' align = "left" />

<h2 onclick = "display(1)">REPORT LEAVING</h2>
<div class = "tab">
	<form method = "POST" action = "<?php echo htmlspecialchars("infanteditprocess.php") ?>">
		
		<label for = "name">Name</label><br />
		<input type = "text" id = "name" name = "name" value = "<?php echo $name; ?>" disabled /><br /><br />
		
		<input type = "hidden" name = "infid" value = "<?php echo $infid ?>"/>
		
		<label for = "report">Leave Date</label><br />
		<input type = "date" id = "report" name = "leavedate" required /><span> * </span><br /><br />

		<input type = "Submit" name = "reportleaving" value = "Submit" />

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