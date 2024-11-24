<?php
session_start();
require_once 'db/db_connection.php';

// Provjera da li je korisnik prijavljen kao administrator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'administrator') {
    header("Location: login.php");
    exit;
}

// Brisanje proizvoda
if (isset($_GET['delete_product'])) {
    $product_id = (int)$_GET['delete_product'];
    $sql = "DELETE FROM products WHERE id = '$product_id'";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Proizvod je uspješno obrisan.";
    } else {
        $error_message = "Greška prilikom brisanja proizvoda: " . $conn->error;
    }
}

// Dohvatanje svih proizvoda
$sql = "SELECT products.*, users.username AS company_name FROM products INNER JOIN users ON products.company_id = users.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Administracija proizvoda</title>
</head>
<body>
    <h1>Administracija proizvoda</h1>

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
            <th>Naziv</th>
            <th>Opis</th>
            <th>Cijena</th>
            <th>Snaga (kWh)</th>
            <th>Kompanija</th>
            <th>Akcije</th>
        </tr>
        <?php while ($product = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td><?php echo $product['price']; ?> KM</td>
                <td><?php echo $product['power']; ?> kWh</td>
                <td><?php echo htmlspecialchars($product['company_name']); ?></td>
                <td>
                    <a href="edit_product_admin.php?id=<?php echo $product['id']; ?>">Izmijeni</a> | 
                    <a href="admin_products.php?delete_product=<?php echo $product['id']; ?>" onclick="return confirm('Da li ste sigurni da želite obrisati ovaj proizvod?');">Obriši</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="dashboard.php">Nazad na dashboard</a>
</body>
</html>
