<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Infants who require under Orphanage" />
<meta name = "keywords" content = "Sponser List, List Infant's Sponser" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "form.css" />

<title>Add Sponserers</title>

</head>

<body>

<div>
<?php
include( 'headlink.htm' );
?>
</div>

<div class = "nestedlinks">
<ul>
<li><button type = "button" onclick = "window.location.href = 'infantslist.php'">Infants List</button></li>
<li><button type = "button" onclick = "window.location.href = 'infantform.php'">Add Infants</button></li>
<li><button type = "button" onclick = "window.location.href = 'requiresponserer.php'">Require Sponsering</button></li>
<li><button type = "button" onclick = "window.location.href = 'leftinfantslist.php'">Left Infants List</button></li>
</ul>
</div>

<h1>ADD SPONSERER FORM</h1>

<div class = "tabcontent">

<?php
$infid = $_POST["infid"];
$name = $_POST["name"];
?>
<form method = "POST" action = "<?php echo htmlspecialchars("sponsererprocess.php"); ?>" >
	
	<input type = "hidden" name = "infid" value = "<?php echo $infid; ?>" />
	
	<label>Name</label><br />
	<input type = "text" name = "name" value = "<?php echo $name; ?>" disabled />
	<br /><br />
	
	<label for = "donor">Sponserers</label><br />
	<select name = "donor" id = "donor" required >
		<option value = "" >--Select One--</optIon>
		<?php
			include('connect.php');
				
				try {
					$conn = new PDO("mysql:host = $server; dbname = $dbname",$username, $password);
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
					$conn->beginTransaction();
				
					$sql ="USE $dbname";
					$conn->exec($sql);
					
					include('orgid.php');
					
					$sql = "SELECT did, fname, lname
							FROM donor
							WHERE orgid = '$orgid'";
							
					$stmt = $conn->prepare($sql);
					$stmt->execute();
	
					$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
					$arr = $stmt->fetchAll();

					foreach($arr as $val) {
						echo '<option value = "'.$val["did"].'">'.ucwords($val["fname"]).' '.ucwords($val["lname"]).'</option>';
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
	
	<input type = "submit" value = "Submit" />
</form>
</div>

<?php include('footer.htm'); ?>
</body>
</html>