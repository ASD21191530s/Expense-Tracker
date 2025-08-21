<?php
require('fpdf182/fpdf.php'); // Ensure FPDF is installed and accessible

// Database connection
$db = new mysqli('localhost', 'root', '', 'expense tracker'); // ✅ use underscore (recommended)
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get Pet ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM transaction WHERE id = $id";
    $result = $db->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 15, 'Transaction Details Report', 0, 1, 'C');
        $pdf->Ln(5);

        // Table-like formatting
        $pdf->SetFont('Arial', '', 12);
        foreach ($row as $key => $value) {
            $label = ucfirst(str_replace(["id","transactiontype","amount","category","date","paymentmethod","notes"], 
                                         ["Transaction ID","Transaction Type","Amount","Category","Date","Payment Method","Notes"], 
                                         $key));
            
            $pdf->Cell(50, 10, $label . ":", 0, 0);
            $pdf->Cell(0, 10, $value, 0, 1);
        }

        // Output PDF
        $pdf->Output();
    } else {
        echo "No pet found with ID $Pid.";
    }   
} else {
    echo "No pet ID provided.";
}
?>
