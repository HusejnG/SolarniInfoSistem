<?php
session_start();
require_once 'db/db_connection.php';

// Provjera da li je korisnik prijavljen kao klijent
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'klijent') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Osvježavanje narudžbi i prikaz novih notifikacija
if (isset($_GET['mark_as_seen'])) {
    $sql_update = "UPDATE orders SET seen_by_client = 1 WHERE user_id = '$user_id'";
    $conn->query($sql_update);
}

// Dohvatanje narudžbi za prijavljenog klijenta
$sql = "SELECT orders.*, products.name AS product_name, products.image AS product_image, products.price AS product_price 
        FROM orders 
        INNER JOIN products ON orders.product_id = products.id 
        WHERE orders.user_id = '$user_id'";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Moje narudžbe</title>
</head>
<body>
    <h1>Moje narudžbe</h1>

    <!-- Novi statusi narudžbi -->
    <?php
    $sql_new_statuses = "SELECT * FROM orders WHERE user_id = '$user_id' AND seen_by_client = 0";
    $new_statuses_result = $conn->query($sql_new_statuses);
    if ($new_statuses_result->num_rows > 0): ?>
        <div style="background-color: #ffcccc; padding: 10px;">
            <p>Imate ažurirane narudžbe! Provjerite dolje:</p>
            <a href="orders.php?mark_as_seen=true">Označi kao pregledano</a>
        </div>
    <?php endif; ?>
    
    <!-- Prikaz svih narudžbi -->
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Slika proizvoda</th>
                <th>Naziv proizvoda</th>
                <th>Cijena</th>
                <th>Količina</th>
                <th>Ukupna cijena</th>
                <th>Status</th>
                <th>Datum narudžbe</th>
            </tr>
            <?php while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($order['product_image']); ?>" alt="Slika proizvoda" width="100"></td>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td><?php echo $order['product_price']; ?> KM</td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td><?php echo $order['total_price']; ?> KM</td>
                    <td><?php echo ucfirst($order['status']); ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Nemate nijednu narudžbu.</p>
    <?php endif; ?>

    <br>
    <a href="klijent.php">Nazad na proizvode</a> | <a href="logout.php">Odjava</a>
</body>
</html>
