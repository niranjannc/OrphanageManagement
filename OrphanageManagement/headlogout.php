<?php 
session_start();

unset($_SESSION["headstate"]);
unset($_SESSION["id"]);

header( 'Location: login.php');

?>