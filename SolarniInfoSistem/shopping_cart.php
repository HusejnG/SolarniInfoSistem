<?php
session_start();
require_once 'db/db_connection.php';

// Provjera da li je korisnik prijavljen kao klijent
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'klijent') {
    header("Location: login.php");
    exit;
}

// Dodavanje proizvoda u korpu
if (isset($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
    header("Location: shopping_cart.php");
    exit;
}

// Prikaz proizvoda u korpi
$products = array();
if (!empty($_SESSION['cart'])) {
    $cart_ids = implode(",", $_SESSION['cart']);
    $sql = "SELECT * FROM products WHERE id IN ($cart_ids)";
    $result = $conn->query($sql);
    while ($product = $result->fetch_assoc()) {
        $products[] = $product;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Korpa za kupovinu</title>
</head>
<body>
    <h1>Vaša korpa</h1>

    <?php if (!empty($products)): ?>
        <table border="1">
            <tr>
                <th>Slika</th>
                <th>Naziv</th>
                <th>Cijena</th>
                <th>Akcije</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Slika proizvoda" width="100"></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo $product['price']; ?> KM</td>
                    <td>
                        <a href="remove_from_cart.php?product_id=<?php echo $product['id']; ?>">Ukloni iz korpe</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a href="checkout.php">Naruči sve</a>
    <?php else: ?>
        <p>Vaša korpa je prazna.</p>
    <?php endif; ?>

    <br>
    <a href="klijent.php">Nazad na proizvode</a>
</body>
</html>
