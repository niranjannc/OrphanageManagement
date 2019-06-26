<?php

if(($_SESSION["headstate"] != "active") or (!isset($_SESSION["headstate"]))) {
	header( 'Location: https://localhost/dbproject/login.php' );
}

?>