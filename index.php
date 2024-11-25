<?php
// Start session
session_start();
require 'db/db_connection.php'; // Include database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proizvodi</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <h1>Dobrodošli na Solarni Info Sistem</h1>

    <?php if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])): ?>
        <div style="text-align: center; margin: 20px 0;">
            <a href="login.php">Prijava</a> | <a href="register.php">Registracija</a>
        </div>
    <?php elseif (isset($_SESSION['admin_id'])): ?>
        <div style="text-align: center; margin: 20px 0;">
            <p>Prijavljeni ste kao administrator.</p>
            <a href="dashboard.php">Idi na Admin Panel</a> | <a href="logout.php">Odjava</a>
        </div>
        <?php exit; // Administratori ne trebaju vidjeti proizvode ?>
    <?php else: ?>
        <div style="text-align: center; margin: 20px 0;">
            <?php if ($_SESSION['role'] === 'klijent'): ?>
                <p>Prijavljeni ste kao klijent.</p>
                <a href="user_profile.php">Moj Profil</a> | <a href="logout.php">Odjava</a>
            <?php elseif ($_SESSION['role'] === 'kompanija'): ?>
                <p>Prijavljeni ste kao kompanija.</p>
                <a href="company_dashboard.php">Idi na Kompanijski Panel</a> | <a href="logout.php">Odjava</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h2>Ponuda Proizvoda</h2>

    <?php
    // Fetch all products
    $products_query = "SELECT * FROM products WHERE deleted_at IS NULL ORDER BY created_at DESC";
    $products = $conn->query($products_query);
    ?>
    <?php if ($products->num_rows > 0): ?>
        <div class="product-grid">
            <?php while ($product = $products->fetch_assoc()): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p><strong><?= number_format($product['price'], 2) ?> KM</strong></p>
                    <form method="POST" action="shopping_cart.php">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <label for="quantity_<?= $product['id'] ?>">Količina:</label>
                        <input type="number" name="quantity" id="quantity_<?= $product['id'] ?>" min="1" value="1" required>
                        <button type="submit">Dodaj u Korpu</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Trenutno nema proizvoda u ponudi.</p>
    <?php endif; ?>

    <a href="shopping_cart.php">Pogledajte svoju korpu</a>
</body>
</html>
