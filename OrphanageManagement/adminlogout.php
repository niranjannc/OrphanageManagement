<?php 
session_start();

unset($_SESSION["adminstate"]);

header( 'Location: login.php');

?>