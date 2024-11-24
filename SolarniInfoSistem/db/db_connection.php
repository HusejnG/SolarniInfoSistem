<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "solarni_paneli";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Konekcija na bazu nije uspjela: " . $conn->connect_error);
}
?>
