<?php

if(($_SESSION["adminstate"] != "active") or (!isset($_SESSION["adminstate"]))) {
	header( 'Location: https://localhost/dbproject/login.php' );
}

?>