<?php
session_start();
require_once 'db/db_connection.php';

// Provjera da li je korisnik prijavljen kao klijent
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'klijent') {
    header("Location: login.php");
    exit;
}

// Provjera je li korpa prazna
if (empty($_SESSION['cart'])) {
    echo "<p>Vaša korpa je prazna. <a href='klijent.php'>Nazad na proizvode</a></p>";
    exit;
}

// Proces narudžbe nakon potvrde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $address = $conn->real_escape_string($_POST['address']);
    
    // Petlja kroz proizvode iz korpe za kreiranje narudžbi
    foreach ($_SESSION['cart'] as $product_id) {
        $sql = "SELECT * FROM products WHERE id = '$product_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $total_price = $product['price'];

            // Insert narudžbe u bazu
            $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, address, status, order_date) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
            $quantity = 1; // Za jednostavnost, svaki proizvod se naručuje po jedanput
            $stmt->bind_param("iiiss", $user_id, $product_id, $quantity, $total_price, $address);
            $stmt->execute();
        }
    }

    // Prazni korpu nakon što je narudžba napravljena
    $_SESSION['cart'] = array();

    echo "<p style='color: green;'>Narudžba je uspješno kreirana! <a href='orders.php'>Pregled narudžbi</a></p>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Završetak kupovine</title>
</head>
<body>
    <h1>Završetak kupovine</h1>
    <form method="POST" action="">
        <label for="address">Unesite adresu dostave:</label><br>
        <textarea name="address" required placeholder="Vaša adresa..."></textarea><br><br>

        <button type="submit">Potvrdi narudžbu</button>
    </form>

    <br>
    <a href="shopping_cart.php">Nazad na korpu</a>
</body>
</html>
