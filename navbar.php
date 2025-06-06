<?php
// navbar.php - dynamiczne menu
?>

<nav>
  <a href="index.php">Strona główna</a>
  <a href="events.php">Wydarzenia</a>
  <a href="cart.php">Koszyk</a>
  <a href="help.php">Pomoc</a>

  <?php if ($role === 'organizer'): ?>
        <a href="organizer/dashboard.php">Panel organizatora</a>
  <?php endif; ?>
  <?php if (isset($_SESSION['user_id'])): ?>
    <a href="profile-klient.php">Profil</a>
    <a href="logout.php">Wyloguj się</a>
  <?php else: ?>
    <a href="login.html">Zaloguj się</a>
  <?php endif; ?>
</nav>
