<?php
session_start();
$role = $_SESSION['role'] ?? null;  // null jeśli nie zalogowany
require_once 'includes/db.php';
$sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Wydarzenia – EventPass</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f0f4f8;
      color: #333;
    }

    header {
      background-color: #0056b3;
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
      max-width: 900px;
      margin: 30px auto;
      padding: 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    h2 {
      margin-top: 0;
      margin-bottom: 20px;
    }

    .event-list {
      list-style-type: none;
      padding-left: 0;
    }

    .event-item {
      border: 1px solid #d1d9e6;
      border-radius: 6px;
      padding: 15px 20px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0,86,179,0.1);
    }

    .event-name {
      font-size: 1.2em;
      font-weight: bold;
      margin: 0 0 8px 0;
    }

    .event-details {
      font-size: 0.9em;
      color: #555;
      margin: 2px 0;
    }

    .event-actions a {
      display: inline-block;
      background-color: #0056b3;
      color: white;
      text-decoration: none;
      padding: 8px 14px;
      border-radius: 4px;
      margin-top: 10px;
    }

    .event-actions a:hover {
      background-color: #003d80;
    }

    footer {
      text-align: center;
      padding: 20px;
      background-color: #f0f0f0;
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <header>
    <h1>EventPass</h1>
    <?php include 'navbar.php'; ?>

  </header>

  <main>
    <h2>Dostępne wydarzenia</h2>
    <section class="event-list">
  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <article class="event-item">
        <div class="event-name"><?= htmlspecialchars($row['title']) ?></div>
        <div class="event-details">Data: <?= date("d.m.Y", strtotime($row['event_date'])) ?></div>
        <div class="event-details">Miejsce: <?= htmlspecialchars($row['location']) ?></div>
        <div class="event-actions">
          <a href="event-details.php?id=<?= $row['id'] ?>">Zobacz szczegóły</a>
        </div>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <p>Brak nadchodzących wydarzeń.</p>
  <?php endif; ?>
</section>

  </main>

  <footer>
    <p>&copy; 2025 EventPass</p>
  </footer>
</body>
</html>
