<?php
require_once 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit;
}

// Pobierz wszystkie wydarzenia i dane biletów
$sql = "
    SELECT 
        e.id, 
        e.title, 
        e.event_date, 
        e.ticket_price, 
        u.username AS organizer_name,
        COUNT(t.id) AS tickets_sold,
        SUM(CASE WHEN t.user_id IS NULL THEN 1 ELSE 0 END) AS tickets_available
    FROM events e
    LEFT JOIN tickets t ON e.id = t.event_id
    JOIN users u ON e.organizer_id = u.id
    GROUP BY e.id
    ORDER BY e.event_date DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Panel administratora – EventShop</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; background: #f9f9f9; }
    header { background: #333; color: #fff; padding: 20px; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin: 20px auto; background: white; }
    th, td { padding: 10px; border: 1px solid #ccc; }
    th { background: #007bff; color: white; }
    .danger { color: red; }
    .actions a { margin-right: 10px; }
    a {
      color: rgb(255, 255, 255);
      text-decoration: none;
    }
    a:visited {
      color: rgb(255, 255, 255);
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
<header>
  <h1>Panel administratora</h1>
  <nav>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="logout.php">Wyloguj się</a>
    <?php endif; ?>
  </nav>
</header>

<main>
  <h2 style="text-align:center;">Lista wydarzeń</h2>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Tytuł</th>
          <th>Data</th>
          <th>Organizator</th>
          <th>Bilety sprzedane</th>
          <th>Bilety dostępne</th>
          <th>Cena biletu</th>
          <th>Opcje</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= date('d.m.Y', strtotime($row['event_date'])) ?></td>
            <td><?= htmlspecialchars($row['organizer_name']) ?></td>
            <td><?= $row['tickets_sold'] ?></td>
            <td><?= $row['tickets_available'] ?></td>
            <td><?= number_format($row['ticket_price'], 2, ',', ' ') ?> PLN</td>
            <td class="actions">
              <a href="delete-event.php?id=<?= $row['id'] ?>" class="danger" onclick="return confirm('Na pewno chcesz usunąć to wydarzenie?');">Usuń</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="text-align:center;">Brak wydarzeń do wyświetlenia.</p>
  <?php endif; ?>
</main>
</body>
</html>
