<?php
session_start();

include('adminlogincheck.php');

?>

<!doctype html>

<html>
<head>

<meta charset = "utf-8" />
<meta name = "description" content = "Admin Account Home Page" />
<meta name = "keywords" content = "Admin Page" />
<meta name = "viewport" content = "width=device-width; initial-scale=1.0" />

<title>Admin Home Page</title>

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
		include( 'adminlink.htm' );
		?>
	</div>

	<p style = "font-size:135%; font-family:'Georgia',Times, serif; margin-right:100px; margin-left:100px;margin-top:70px;" >
		Our orphanage history is one of great adventures, overcoming huge odds and most importantly love. Orphanages rarely have a story to tell like ours does; some might find parts of it hard to believe but we assure you, it is all truth.
		<br /><br />
		Our Trust, Vivekananda Trust, was founded in 1995 by Sri Vivekananda Patil. The trust has been contributing to the nation through many activities. One among them is establishing orphanages and providing many infants home and shelter.
		<br /><br />
		The trust, Vivekananda Trust, decided to help infants and orphans and laid foundation to build orhanage across the country. Likewise many orphanages have been established and are working for the orphans welfare.
		<br /><br />
		The Vivekananda Trust not only has established orphanages, but also has established schools, colleges, hospitals and welfare associations.
		<br /><br />
		The Vivekananda Trust is completely privately run. No corporation or organization consistently supports or sponsors the work.
	</p>
	
	<?php include('footer.htm'); ?>
</body>
</html>