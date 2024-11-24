<?php
session_start();
include 'db/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
        } else {
            echo "Pogrešna šifra.";
        }
    } else {
        echo "Korisničko ime ne postoji.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Prijava</title>
</head>
<body>
    <h2>Prijava</h2>
    <form method="POST" action="">
        <label for="username">Korisničko ime:</label>
        <input type="text" name="username" required><br>
        
        <label for="password">Šifra:</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">Prijavi se</button>
    </form>
</body>
</html>
