<?php
session_start();

// Usuń wszystkie dane sesji
$_SESSION = [];

// Zniszcz sesję
session_destroy();

// Przekieruj na stronę startową dla gości
header("Location: index.php"); // lub "index.html" jeśli tam masz stronę gościa
exit;
