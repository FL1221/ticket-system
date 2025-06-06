<?php
session_start();

$role = $_SESSION['role'] ?? null;  // null jeśli nie zalogowany
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Pomoc – EventPass</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; background-color: #f9f9f9; color: #333; }
    header { background-color: #007bff; color: white; padding: 20px 30px; text-align: center; }
    nav a { color: white; text-decoration: none; margin: 0 12px; font-weight: bold; }
    nav a:hover { text-decoration: underline; }
    main { max-width: 800px; margin: 30px auto; padding: 0 20px; background: white; border-radius: 8px; box-shadow: 0 0 12px rgba(0,0,0,0.1); }
    h1, h2 { margin-top: 0; }
    ul { list-style-type: none; padding-left: 0; }
    ul li { background: #e9f0ff; margin: 10px 0; padding: 12px 15px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,123,255,0.2); }
    footer { text-align: center; padding: 15px 10px; font-size: 0.9em; color: #666; border-top: 1px solid #ddd; margin-top: 40px; }
  </style>
</head>
<body>
  <header>
    <h1>EventPass – Pomoc</h1>
    <nav>
      <a href="index.php">Strona główna</a>
      <a href="help.php">Pomoc</a>
      <?php if ($role === null): ?>
        <a href="login.html">Zaloguj się</a>
        <a href="register.html">Zarejestruj się</a>
      <?php elseif ($role === 'organizer'): ?>
        <a href="organizer/dashboard.php">Panel organizatora</a>
        <a href="logout.php">Wyloguj się</a>
      <?php else: ?>
        <a href="logout.php">Wyloguj się</a>
      <?php endif; ?>
    </nav>
  </header>

  <main>
    <?php if ($role === 'organizer'): ?>
      <section>
        <h2>Pomoc dla Organizatorów</h2>
        <ul>
          <li><strong>Jak dodać wydarzenie?</strong><br> Przejdź do zakładki "Dodaj wydarzenie" i wypełnij formularz.</li>
          <li><strong>Jak edytować wydarzenie?</strong><br> W zakładce "Moje wydarzenia" kliknij „Edytuj” przy wybranym wydarzeniu.</li>
          <li><strong>Jak sprawdzić sprzedaż biletów?</strong><br> Statystyki znajdziesz na swoim panelu organizatora.</li>
          <li><strong>Problemy z edycją lub usuwaniem wydarzeń?</strong><br> Upewnij się, że masz poprawne uprawnienia i działające połączenie z serwerem.</li>
        </ul>
      </section>
    <?php elseif ($role === 'client'): ?>
      <section>
        <h2>Pomoc dla Klientów</h2>
        <ul>
          <li><strong>Jak kupić bilet?</strong><br> Wybierz wydarzenie i kliknij „Kup bilet”.</li>
          <li><strong>Jak wyświetlić swoje bilety?</strong><br> Przejdź do zakładki „Moje bilety” w swoim profilu.</li>
          <li><strong>Jak zwrócić bilet?</strong><br> Skontaktuj się z organizatorem wydarzenia lub z pomocą techniczną.</li>
          <li><strong>Problemy z zakupem?</strong><br> Sprawdź połączenie internetowe i spróbuj ponownie, lub skontaktuj się z nami.</li>
        </ul>
      </section>
    <?php else: ?>
      <section>
        <h2>Pomoc dla Gości</h2>
        <ul>
          <li><strong>Jak założyć konto?</strong><br> Kliknij "Zarejestruj się" w menu i wypełnij formularz rejestracji.</li>
          <li><strong>Co mogę robić bez konta?</strong><br> Możesz przeglądać dostępne wydarzenia i dowiedzieć się więcej o naszej platformie.</li>
          <li><strong>Dlaczego warto się zarejestrować?</strong><br> Rejestracja pozwala na zakup biletów, śledzenie wydarzeń i korzystanie z dodatkowych funkcji.</li>
          <li><strong>Masz pytania?</strong><br> Skontaktuj się z nami, a pomożemy Ci zacząć.</li>
        </ul>
      </section>
    <?php endif; ?>

    <section>
      <h2>Kontakt</h2>
      <p>Masz problem lub pytanie? Skontaktuj się z nami:</p>
      <ul>
        <li>Email: pomoc@eventpass.pl</li>
        <li>Telefon: +48 123 456 789</li>
        <li>Godziny pracy: Pon–Pt, 9:00–17:00</li>
      </ul>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 EventPass. Wszelkie prawa zastrzeżone.</p>
  </footer>
</body>
</html>
