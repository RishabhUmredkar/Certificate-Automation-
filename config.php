<?php 
session_start();


$servername = "localhost:3307";
$username = "root";
$password = "";
$databasename = "virtual_intern";

// Create connection
$con = mysqli_connect($servername, $username, $password, $databasename);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
