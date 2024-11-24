<?php
session_start();
require_once 'db/db_connection.php';

// Provjera da li je korisnik prijavljen kao administrator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'administrator') {
    header("Location: login.php");
    exit;
}

// Brisanje korisnika
if (isset($_GET['delete_user'])) {
    $user_id = (int)$_GET['delete_user'];
    $sql = "DELETE FROM users WHERE id = '$user_id'";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Korisnik je uspješno obrisan.";
    } else {
        $error_message = "Greška prilikom brisanja korisnika: " . $conn->error;
    }
}

// Dohvatanje svih korisnika
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Administracija korisnika</title>
</head>
<body>
    <h1>Administracija korisnika</h1>

    <!-- Poruke o uspjehu ili grešci -->
    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Korisničko ime</th>
            <th>Uloga</th>
            <th>Akcije</th>
        </tr>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo ucfirst($user['role']); ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['id']; ?>">Izmijeni</a> | 
                    <a href="admin_users.php?delete_user=<?php echo $user['id']; ?>" onclick="return confirm('Da li ste sigurni da želite obrisati ovog korisnika?');">Obriši</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="admin_add_company.php">Dodaj novu kompaniju</a> | <a href="logout.php">Odjava</a>
</body>
</html>
