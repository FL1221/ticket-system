<?php
session_start();
$role = $_SESSION['role'] ?? null;  // null jeśli nie zalogowany
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>EventPass – Strona Główna</title>
  <style>
    /* Podstawowy reset i font */
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

    .btn {
      display: inline-block;
      background-color: #007bff;
      color: white;
      padding: 10px 15px;
      border-radius: 6px;
      text-decoration: none;
      margin-top: 20px;
      font-weight: bold;
    }

    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <header>
    <h1>EventPass</h1>
    <?php include 'navbar.php'; ?>
  </header>

  <main>
    <section>
      <h2>Witamy w EventPass</h2>
      <p>Kup bilety na najlepsze wydarzenia na świecie!</p>
      <a href="events.php" class="btn">Polecane wydarzenia</a>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 EventPass. Wszelkie prawa zastrzeżone.</p>
  </footer>
</body>
</html>
