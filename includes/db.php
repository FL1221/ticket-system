<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "ticketing_db"; 

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}
?>
