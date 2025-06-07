<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    // jeśli nie zalogowany, przekieruj do strony dla gości (lub loginu)
    header("Location: index_guest.php");
    exit;
}

// Pobierz dane użytkownika z bazy (np. imię)
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = htmlspecialchars($user['username']);
$role = $user['role'];  // np. do pokazywania dodatkowych opcji, jeśli admin/organizer
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>EventPass – Strona Główna</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f9f9f9;
      color: #333;
    }

    header {
      background-color: #007bff;
      color: white;
      padding: 20px 30px;
      text-align: center;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 0 12px;
      font-weight: bold;
    }

    nav a:hover {
      text-decoration: underline;
    }

    main {
      max-width: 800px;
      margin: 30px auto;
      padding: 0 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }

    h1, h2 {
      margin-top: 0;
    }

    ul {
      list-style-type: none;
      padding-left: 0;
    }

    ul li {
      background: #e9f0ff;
      margin: 10px 0;
      padding: 12px 15px;
      border-radius: 6px;
      box-shadow: 0 1px 3px rgba(0,123,255,0.2);
    }

    footer {
      text-align: center;
      padding: 15px 10px;
      font-size: 0.9em;
      color: #666;
      border-top: 1px solid #ddd;
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <header>
    <h1>EventPass</h1>
    <nav>
      <a href="index_klient.php">Strona główna</a>
      <a href="events.php">Wydarzenia</a>
      <a href="cart.php">Koszyk</a>
      <a href="profile-klient.php">Profil </a>
      <?php if ($role === 'organizer' || $role === 'admin'): ?>
        <a href="organizer/dashboard.php">Panel organizatora</a>
      <?php endif; ?>
      <a href="logout.php">Wyloguj się</a>
    </nav>
  </header>

  <main>
    <section>
      <h2>Witaj, <?= $username ?>!</h2>
      <p>Dziękujemy, że jesteś z nami. Kup bilety na najlepsze wydarzenia!</p>
      <a href="events.php" class="btn">Przeglądaj wydarzenia</a>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 EventPass. Wszelkie prawa zastrzeżone.</p>
  </footer>
</body>
</html>
