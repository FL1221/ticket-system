<?php
session_start();
$role = $_SESSION['role'] ?? null;  // null jeśli nie zalogowany
require_once 'includes/db.php';

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Pobieramy aktywne bilety (na przyszłe wydarzenia)
$sql_active = "
    SELECT t.id AS ticket_id, e.title, e.event_date, t.purchase_date
    FROM tickets t
    JOIN events e ON t.event_id = e.id
    WHERE t.user_id = $user_id AND e.event_date >= CURDATE()
    ORDER BY e.event_date ASC
";

$result_active = $conn->query($sql_active);

// Pobieramy archiwalne bilety (na wydarzenia w przeszłości)
$sql_archive = "
    SELECT t.id AS ticket_id, e.title, e.event_date, t.purchase_date
    FROM tickets t
    JOIN events e ON t.event_id = e.id
    WHERE t.user_id = $user_id AND e.event_date < CURDATE()
    ORDER BY e.event_date DESC
";

$result_archive = $conn->query($sql_archive);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Profil klienta – EventPass</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 0;
      color: #333;
    }
    header {
      background: #007bff;
      color: white;
      padding: 15px 30px;
      text-align: center;
    }
    nav a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
      font-weight: bold;
    }
    nav a:hover {
      text-decoration: underline;
    }
    main {
      max-width: 900px;
      margin: 30px auto;
      background: white;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    h2 {
      border-bottom: 2px solid #007bff;
      padding-bottom: 6px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background: #e9f0ff;
    }
    .empty-msg {
      margin-top: 15px;
      font-style: italic;
      color: #777;
    }
    .btn-download {
      background-color: #007bff;
      color: white;
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 4px;
      font-size: 0.9em;
    }
    .btn-download:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <header>
    <h1>EventPass – Profil klienta</h1>
    <?php include 'navbar.php'; ?>
  </header>

  <main>
    <section>
      <h2>Twoje aktywne bilety</h2>
      <?php if ($result_active && $result_active->num_rows > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Wydarzenie</th>
              <th>Data wydarzenia</th>
              <th>Data zakupu</th>
              <th>Akcja</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result_active->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= date("d.m.Y", strtotime($row['event_date'])) ?></td>
                <td><?= date("d.m.Y", strtotime($row['purchase_date'])) ?></td>
                <td><a href="download-ticket.php?ticket_id=<?= $row['ticket_id'] ?>" class="btn-download">Pobierz bilet</a></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="empty-msg">Brak aktywnych biletów.</p>
      <?php endif; ?>
    </section>

    <section style="margin-top: 40px;">
      <h2>Historia Twoich wydarzeń</h2>
      <?php if ($result_archive && $result_archive->num_rows > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Wydarzenie</th>
              <th>Data wydarzenia</th>
              <th>Data zakupu</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result_archive->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= date("d.m.Y", strtotime($row['event_date'])) ?></td>
                <td><?= date("d.m.Y", strtotime($row['purchase_date'])) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="empty-msg">Brak historii wydarzeń.</p>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
