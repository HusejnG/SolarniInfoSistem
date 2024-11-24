<?php
session_start();
require_once 'db/db_connection.php';

// Provjerite je li korisnik prijavljen kao kompanija
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'kompanija') {
    header("Location: login.php");
    exit;
}

// Provjera da li je ID proizvoda naveden
if (!isset($_GET['id'])) {
    echo "Greška: ID proizvoda nije naveden.";
    exit;
}

$product_id = $_GET['id'];

// Dohvati podatke o proizvodu
$sql = "SELECT * FROM products WHERE id = '$product_id' AND company_id = '{$_SESSION['user_id']}'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Proizvod nije pronađen ili nemate dozvolu za izmjenu.";
    exit;
}

$product = $result->fetch_assoc();

// Ažuriranje proizvoda
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (float) $_POST['price'];
    $power = (float) $_POST['power'];

    // Provjera da li je dodana nova slika
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        // Ažuriraj sa novom slikom
        $sql = "UPDATE products SET 
                name = '$name', 
                description = '$description', 
                price = $price, 
                power = $power, 
                image = '$target_file' 
                WHERE id = '$product_id' AND company_id = '{$_SESSION['user_id']}'";
    } else {
        // Ažuriraj bez izmjene slike
        $sql = "UPDATE products SET 
                name = '$name', 
                description = '$description', 
                price = $price, 
                power = $power 
                WHERE id = '$product_id' AND company_id = '{$_SESSION['user_id']}'";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Proizvod je uspješno izmijenjen!</p>";
    } else {
        echo "<p style='color: red;'>Greška prilikom izmjene proizvoda: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Izmjena proizvoda</title>
</head>
<body>
    <h2>Izmjena proizvoda</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Naziv proizvoda:</label>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
        
        <label for="description">Opis proizvoda:</label>
        <textarea name="description" required><?php echo $product['description']; ?></textarea><br>
        
        <label for="price">Cijena (KM):</label>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required><br>
        
        <label for="power">Snaga (kWh):</label>
        <input type="number" step="0.01" name="power" value="<?php echo $product['power']; ?>" required><br>
        
        <label for="image">Nova slika proizvoda (opcionalno):</label>
        <input type="file" name="image"><br>
        
        <button type="submit">Ažuriraj proizvod</button>
    </form>
</body>
</html>
