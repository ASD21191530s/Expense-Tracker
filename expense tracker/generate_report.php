<?php
require("fpdf182/fpdf.php");

// Database connection
$conn = new mysqli("localhost", "root", "", "expense tracker");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}  

// Fetch all transactions
$result = $conn->query("SELECT * FROM transaction ORDER BY date DESC");

// Initialize PDF  
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 16);

// Title
$pdf->Cell(0, 10, "Transaction History Report", 0, 1, "C");
$pdf->Ln(10);

// Table Header (make widths consistent)
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(40, 10, "Transaction Type", 1);
$pdf->Cell(40, 10, "Amount", 1);
$pdf->Cell(40, 10, "Category", 1);
$pdf->Cell(40, 10, "Date", 1);
$pdf->Cell(60, 10, "Notes", 1);
$pdf->Ln();

// Table Body
$pdf->SetFont("Arial", "", 12);
while($row = $result->fetch_assoc()) {
    $pdf->Cell(40, 10, $row['transactiontype'], 1);
    $pdf->Cell(40, 10, number_format($row['amount'], 1), 1, 0, "R");
    $pdf->Cell(40, 10, $row['category'], 1);
    $pdf->Cell(40, 10, $row['date'], 1);
    $pdf->Cell(60, 10, $row['notes'], 1);
    $pdf->Ln();
}

// Show PDF inline in browser
$pdf->Output("I", "Transaction_History.pdf");
?>
    