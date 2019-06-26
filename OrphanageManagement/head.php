<?php
session_start();

include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "utf-8" />
<meta name = "description" content = "Head Account Home Page" />
<meta name = "keywords" content = "Head Page" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<title>Head Home Page</title>

<link rel = "stylesheet" type = "text/css" href = "links.css" />
<!--
<script>

function logout() {
	window.location.href = "logout.php";
}

</script>
-->

</head>

<body>

	<div>
		<?php
		include( 'headlink.htm' );
		?>
	</div>
	
	<?php
		include('connect.php');
		try {
			$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
			$conn->beginTransaction();
			
			$sql = "USE $dbname";
			$conn->exec($sql);
			include('orgid.php');
			
			$sql = "SELECT *
					FROM orphanage
					WHERE orgid='$orgid'";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
	
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$arr = $stmt->fetchAll();
			$val = $arr[0];
			
			//print_r($arr);
			$orgid = $val["orgid"];
			$name = $val["name"];
			$addlocation = $val["addlocation"];
			$addcity = $val["addcity"];
			$adddistrict = $val["adddistrict"];
			$addstate = $val["addstate"];
			$phone = $val["phone"];
			$email = $val["email"];
			$estyear = $val["estyear"];
			$trust = $val["trust"];
			
			$conn->commit();
			$conn=null;
		}
		catch(PDOException $e) {
			$conn->rollback();
			echo $e->getMessage();
		}
	
	?>

	<p style = "font-size:135%; font-family:'Georgia',Times, serif; margin-right:100px; margin-left:100px;margin-top:70px;" >
		Our orphanage history is one of great adventures, overcoming huge odds and most importantly love. Orphanages rarely have a story to tell like ours does; some might find parts of it hard to believe but we assure you, it is all truth.
		<br /><br />
		Our Orphanage was founded in <?php echo $estyear; ?> by <?php echo $trust; ?>. The trust has been contributing to the nation through many activities. One among them is establishing orphanages and providing many infants home and shelter.
		<br /><br />
		The trust <?php echo $trust; ?> decided to help infants and orphans and laid foundation to build orhanage across the country. Likewise our orphanage <?php echo ucwords($name); ?> started working in <?php echo $estyear; ?> in <?php echo ucwords($adddistrict.", ".$addstate); ?>.
		<br /><br />
		The <?php echo $trust;?> is completely privately run. No corporation or organization consistently supports or sponsors the work.
	</p>
	
	<?php include('footer.htm'); ?>
</body>
</html>