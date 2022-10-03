<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = "novacrawler";

// Create connection
Global $con;
$con= mysqli_connect($servername, $username, $password,$db);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


?>