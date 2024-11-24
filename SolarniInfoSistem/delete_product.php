<?php
session_start();
require_once 'db/db_connection.php';

// Provjerite je li korisnik prijavljen kao kompanija
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'kompanija') {
    header("Location: login.php");
    exit;
}

// Provjera ID proizvoda
if (!isset($_GET['id'])) {
    echo "Greška: ID proizvoda nije naveden.";
    exit;
}

$product_id = $_GET['id'];

// Provjera vlasništva nad proizvodom
$sql = "SELECT * FROM products WHERE id = '$product_id' AND company_id = '{$_SESSION['user_id']}'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Proizvod nije pronađen ili nemate dozvolu za brisanje.";
    exit;
}

// Brisanje proizvoda
$sql = "DELETE FROM products WHERE id = '$product_id'";
if ($conn->query($sql) === TRUE) {
    header("Location: kompanija.php?message=deleted");
    exit;
} else {
    echo "<p style='color: red;'>Greška prilikom brisanja proizvoda: " . $conn->error . "</p>";
}
?>
