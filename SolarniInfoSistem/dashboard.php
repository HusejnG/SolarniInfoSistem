<?php
session_start();
require_once 'db/db_connection.php';

// Provjera da li je korisnik prijavljen
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Provjera uloge
$role = $_SESSION['role'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Dobrodošli na dashboard, <?php echo $_SESSION['username']; ?>!</h1>
    
    <?php if ($role == 'kompanija'): ?>
        <h2>Kompanijski panel</h2>
        <a href="kompanija.php">Prikaz i upravljanje proizvodima</a>
    <?php elseif ($role == 'klijent'): ?>
        <h2>Kupovni panel</h2>
        <a href="klijent.php">Pregled proizvoda</a>
    <?php endif; ?>

    <br><br>
    <a href="logout.php">Odjava</a>
</body>
</html>
