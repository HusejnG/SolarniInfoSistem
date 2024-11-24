<?php
session_start();
require_once 'db/db_connection.php';

// Provjera da li je korisnik prijavljen
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Korisnik nije pronađen.";
    exit;
}

$user = $result->fetch_assoc();

// Ažuriranje korisničkih podataka
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql_update = "UPDATE users SET username = '$username', email = '$email', password = '$password' WHERE id = '$user_id'";
    } else {
        $sql_update = "UPDATE users SET username = '$username', email = '$email' WHERE id = '$user_id'";
    }

    if ($conn->query($sql_update) === TRUE) {
        echo "<p style='color: green;'>Korisnički podaci su uspješno ažurirani.</p>";
    } else {
        echo "<p style='color: red;'>Greška prilikom ažuriranja korisnika: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Moj profil</title>
</head>
<body>
    <h2>Moj profil</h2>
    <form method="POST" action="">
        <label for="username">Korisničko ime:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

        <label for="email">E-mail:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label for="password">Nova lozinka (ostavite prazno ako ne želite promijeniti):</label>
        <input type="password" name="password"><br><br>

        <button type="submit">Ažuriraj podatke</button>
    </form>

    <br>
    <a href="dashboard.php">Nazad na dashboard</a>
</body>
</html>
