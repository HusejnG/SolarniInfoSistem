<?php
session_start();
require_once 'db/db_connection.php';

// Provjera da li je korisnik prijavljen kao administrator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'administrator') {
    header("Location: login.php");
    exit;
}

// Dodavanje kompanije
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'kompanija')";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Kompanija je uspješno dodana!</p>";
    } else {
        echo "<p style='color: red;'>Greška: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Dodavanje kompanije</title>
</head>
<body>
    <h2>Dodavanje nove kompanije</h2>
    <form method="POST">
        <label for="username">Korisničko ime:</label>
        <input type="text" name="username" required><br>
        
        <label for="password">Šifra:</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">Dodaj kompaniju</button>
    </form>

    <br>
    <a href="dashboard.php">Nazad na dashboard</a>
</body>
</html>
