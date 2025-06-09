<?php
session_start();
require_once 'includes/db.php';
require_once 'libs/fpdf.php';  // Upewnij się, że ścieżka jest poprawna

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : null;
if (!$ticket_id) {
    die('Brakuje ID biletu.');
}

$sql = "
    SELECT t.id AS ticket_id, e.title, e.event_date, e.location, e.description
    FROM tickets t
    JOIN events e ON t.event_id = e.id
    WHERE t.user_id = ? AND t.id = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Tworzenie PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(0, 10, 'Bilet na wydarzenie', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Wydarzenie: ' . $row['title'], 0, 1);
    $pdf->Cell(0, 10, 'Opis: ' . $row['description'], 0, 1);
    $pdf->Cell(0, 10, 'Data: ' . date('Y-m-d', strtotime($row['event_date'])), 0, 1);
    $pdf->Cell(0, 10, 'Miejsce: ' . $row['location'], 0, 1);
    $pdf->Cell(0, 10, 'ID biletu: ' . $row['ticket_id'], 0, 1);
    $pdf->Cell(0, 10, 'ID klienta: ' . $user_id, 0, 1);

    $pdf->Ln(5);
    $pdf->MultiCell(0, 10, 'Opis wydarzenia: ' . $row['description']);

    $pdf->Output('I', 'bilet.pdf');
} else {
    echo "Nie znaleziono biletu.";
}
?>
