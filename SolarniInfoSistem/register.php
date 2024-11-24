<?php
include 'db/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "Registracija uspješna! <a href='login.php'>Prijavite se ovdje</a>";
    } else {
        echo "Greška: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Registracija</title>
</head>
<body>
    <h2>Registracija</h2>
    <form method="POST" action="">
        <label for="username">Korisničko ime:</label>
        <input type="text" name="username" required><br>
        
        <label for="password">Šifra:</label>
        <input type="password" name="password" required><br>
        
        <label for="role">Uloga:</label>
        <select name="role" required>
            <option value="klijent">Klijent</option>
            <option value="kompanija">Kompanija</option>
        </select><br>
        
        <button type="submit">Registruj se</button>
    </form>
</body>
</html>
