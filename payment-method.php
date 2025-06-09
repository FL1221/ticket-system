<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "Koszyk jest pusty. <a href='events.php'>Wróć do wydarzeń</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wybór metody płatności</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        form { display: inline-block; text-align: left; padding: 20px; border: 1px solid #ccc; background: #f9f9f9; }
        button { padding: 10px 20px; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Wybierz metodę płatności</h1>
    <form action="buy.php" method="post">
        <label>
            <input type="radio" name="payment_method" value="blik" required> BLIK
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="karta"> Karta płatnicza
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="przelew"> Przelew tradycyjny
        </label><br>

        <button type="submit">Przejdź do płatności</button>
    </form>
</body>
</html>
