<?php
session_start();
require_once '../includes/db.php';

// Sprawdzenie, czy organizator jest zalogowany
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: ../login.html");
    exit;
}

$userId = $_SESSION['user_id'];

$sqlEvents = "
    SELECT 
        e.id, 
        e.title, 
        e.event_date, 
        e.tickets_available AS total_tickets, 
        e.ticket_price,
        COUNT(t.id) AS tickets_sold
    FROM events e
    LEFT JOIN tickets t ON e.id = t.event_id AND t.user_id IS NOT NULL
    WHERE e.organizer_id = $userId
    GROUP BY e.id
    ORDER BY e.event_date DESC
";


$resultEvents = $conn->query($sqlEvents);
// Statystyki: liczba wydarzeń, liczba sprzedanych biletów i łączny dochód
$sqlStats = "
    SELECT 
      COUNT(*) AS event_count, 
      COALESCE(SUM(tickets_sold), 0) AS tickets_sold, 
      COALESCE(SUM(tickets_sold * ticket_price), 0) AS total_income
    FROM events
    WHERE organizer_id = $userId
";
$resultStats = $conn->query($sqlStats);
$stats = $resultStats->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Panel organizatora – EventShop</title>
  <style>
    /* tu możesz wkleić swój styl z dashboard.html */
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
    h1, h2, h3 {
      margin-top: 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    a.button {
      display: inline-block;
      margin-top: 15px;
      background-color: #007bff;
      color: white;
      padding: 8px 12px;
      text-decoration: none;
      border-radius: 4px;
    }
    a.button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <header>
    <h1>EventPass – Panel organizatora</h1>
    <nav>
      <a href="create-event.html">Dodaj wydarzenie</a>
      <a href="dashboard.php">Moje wydarzenia</a>
      <a href="profile.html">Mój profil</a>
      <a href="../help.php">Pomoc</a>
      <a href="../logout.php">Wyloguj się</a>
    </nav>
  </header>

  <main>
    <h2>Witaj w panelu organizatora!</h2>

    <section class="events-list">
      <h3>Twoje wydarzenia</h3>
      <?php if ($resultEvents->num_rows > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Tytuł</th>
              <th>Data</th>
              <th>Dostępne bilety</th>
              <th>Sprzedane bilety</th>
              <th>Cena biletu</th>
              <th>Opcje</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $resultEvents->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= date("d.m.Y", strtotime($row['event_date'])) ?></td>
                <td><?= max(0, $row['total_tickets'] - $row['tickets_sold']) ?></td>
                <td><?= $row['tickets_sold'] ?></td>
                <td><?= number_format($row['ticket_price'], 2, ',', ' ') ?> PLN</td>
                <td><a href="edit-event.html?id=<?= $row['id'] ?>">Edytuj</a></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>Nie masz jeszcze żadnych wydarzeń.</p>
      <?php endif; ?>
    </section>
  </main>


  <footer>
    <p>&copy; 2025 EventPass</p>
  </footer>
</body>
</html>
