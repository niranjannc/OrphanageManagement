<?php
session_start();
include('headlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "UTF-8" />
<meta name = "description" content = "Search Infants" />
<meta name = "keywords" content = "Adoption, Infants" />
<meta name = "viewport" content = "width=device-width, initial-scale=1.0" />

<link rel = 'stylesheet' type = "text/css" href = "links.css" />
<link rel = "stylesheet" type = "text/css" href = "table.css" />

<title>Searching Infats</title>

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

<h1>INFANTS LIST</h1>

<div class = "tabcontent">

<?php
include('connect.php');

$pid = testData($_POST["pid"]);
$fname = testData($_POST["fname"]);
$mname = testData($_POST["mname"]);
$agereq = testData($_POST["agereq"]);
$genreq = testData($_POST["genreq"]);

echo '<h3 class="tabfont" style="color:blue">
		Parent(s) : '.$fname.'<span style="margin-left:30px">'.$mname.'</span><br />
		Age Requiremnet : '.($agereq-1).' - '.($agereq+1).'<br />
		Gender Requirement : ';
			if($genreq=="m")
				echo "Male";
			else if($genreq=='f')
				echo "Female";
			else
				echo "Others";
		echo '</h3><br />';

try {
	$conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE $dbname"; 
	
	$conn->beginTransaction();
	$conn->exec($sql);
	//include('orgid.php');
	
	$sql = "SELECT i.infid, i.fname, i.lname, i.gender,
			truncate(((curdate()-i.bdate)/10000),0) as age
			FROM parents p, infant i
			WHERE i.orgid = p.orgid AND i.hasleft='n'
			AND i.gender = p.genreq AND p.pid = '$pid' AND
			truncate(((curdate()-i.bdate)/10000),0) BETWEEN (p.agereq-1) AND (p.agereq+1)
			AND (truncate(((curdate()-p.fbdate)/10000),0)-truncate(((curdate()-i.bdate)/10000),0)>25 || truncate(((curdate()-p.mbdate)/10000),0)-truncate(((curdate()-i.bdate)/10000),0)>25)";
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$arr = $stmt->fetchAll();
	
	echo "<table style = 'width:100%'>
			<tr>
				<th width='30%'>Infant</th>
				<th width='20%'>Gender</th>
				<th width='20%'>Age</th>
				<th width='15%'>Details</th>
				<th width='15%'>Adoption</th>
			</tr>";
	
	foreach($arr as $val) {
		echo '<tr>
				<td class="tabfont">'.ucwords($val["fname"])." ".ucwords($val["lname"]).'</td>
				<td class="tabfont">';
					if($val["gender"]=="m")
						echo "Male";
					else if($val["gender"]=="f")
						echo "Female";
					else if($val["gender"]=="o")
						echo "Others";
				echo '</td>
				<td class="tabfont">'.$val["age"].'</td>
				<td>
					<form method = "POST" action = "infantdetails.php" target = "_blank">
						<input type="hidden" name="infid" value="'.$val["infid"].'"/>
						<input type="submit" value="View" class = "tabsubmit" />
					</form>
				</td>
				<td>
					<form method = "POST" action = "adoption.php">
						<input type="hidden" name="pid" value="'.$pid.'"/>
						<input type="hidden" name="fname" value="'.$fname.'" />
						<input type="hidden" name="mname" value="'.$mname.'" />
						<input type="hidden" name="infid" value="'.$val["infid"].'"/>
						<input type="hidden" name="iname" value="'.$val["fname"].' '.$val["lname"].'" />
						<input type="submit" value="Adopt" class = "tabsubmit" />
					</form>
				</td>
			</tr>';
	}
	echo '</table>';
	
	if(sizeof($arr)==0) {
		echo '<h3 class="nodata" >No Infant matching the requirements was found</h3>';
	}
	$conn->commit();
	$conn=null;
}
catch(PDOException $e) {
	$conn->rollback();
	echo $e->getMessage();
}

?>

</div>
<?php include('footer.htm'); ?>
</body>
</html>