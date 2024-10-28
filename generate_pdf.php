<?php


require_once('vendor/autoload.php');
// Adjust the path to TCPDF as needed

// Define the date range (replace these with your actual dates)
$startDate = $_GET["fromDate"];
$endDate = $_GET["toDate"];


// Create a new TCPDF instance
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Issued Items Report');
$pdf->SetSubject('Issued Items Report');
$pdf->SetKeywords('PDF, Issued Items, Report');

// Set default header data
$pdf->SetHeaderData('', 0, 'Issued Items Report', 'Generated on ' . date('Y-m-d H:i:s'));

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add a page
$pdf->AddPage();

// Fetch issued items within the date range from your database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventorymanagement";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch issued items within the date range
$sql = "SELECT * FROM issue WHERE date >= '$startDate' AND date <= '$endDate'";
$result = $conn->query($sql);

// Table header
$html = '<table border="1">
    <tr>
        <th>Date</th>
        <th>Item Name</th>
        <th>Department</th>
        <th>Quantity</th>
        <th>Receiver</th>
        <th>Issued By</th>
    </tr>';

// Table rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
            <td>' . $row['date'] . '</td>
            <td>' . $row['item_name'] . '</td>
            <td>' . $row['department'] . '</td>
            <td>' . $row['quantity'] . '</td>
            <td>' . $row['receiver'] . '</td>
            <td>' . $row['issued_by'] . '</td>
        </tr>';
    }
}

$html .= '</table>';

// Output the HTML content to the PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output the PDF
$pdf->Output('Issued_Items_Report.pdf', 'I');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issued Items Report</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS file -->
    <style>
        /* CSS for date input fields */
        input[type="date"] {
            
            width: 200px; /* Adjust the width as needed */
        }
    </style>
</head>
<body>
<script src="script.js"></script>

<div id="sidebar">
    <header>
        <img src="development.png" alt="Company Logo">
        <h1>Menu</h1>
    </header>
    <nav>
        <div class="nav-option"><a href="add.php" class="nav-button">Add Item</a></div>
        <div class="nav-option"><a href="issue.php" class="nav-button">Issue Item</a></div>
        <div class="nav-option"><a href="desktop.php" class="nav-button">Issue Desktop</a></div>
        <div class="nav-option"><a href="stock.php" class="nav-button">Stock</a></div>
        <div class="nav-option"><a href="scrap.php" class="nav-button">Scrap</a></div>
        <div class="nav-option"><a href="report.php" class="nav-button" style="background-color: #3498db;">Report</a></div>
    </nav>
</div>
<div id="content">
    <header style="background-color:  #979292;">
        <img src="imgcl.png" alt="Company Logo">
    </header>
    <header>
  
          

        Issued Items Report
    </header>
    <section id="date-range-filter">
        <form method="post" action="">
            <label for="from-date"style="font-size: 20px;">From:</label>
            <input type="date" id="from-date" name="from-date" value="<?php echo $fromDate; ?>" required>
            <label for="to-date"style="font-size: 20px;">To:</label>
            <input type="date" id="to-date" name="to-date" value="<?php echo $toDate; ?>" required>
            <button type="submit"class="solid-button"style="font-size: 12px; background-color: #3498db;">Filter</button>
        </form>
    </section>
    <section id="issued-items-list">
        <?php
        $logoPath = 'imgcl.png'; // Adjust the filename and path as needed

        if (isset($result) && $result->num_rows > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>Date</th>";
            echo "<th>Item Name</th>";
            echo "<th>Department</th>";
            echo "<th>Quantity</th>";
            echo "<th>Receiver</th>";
            echo "<th>Issued By</th>";
            echo "</tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["item_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["department"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["receiver"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["issued_by"]) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No issued items found within the selected date range.</p>";
        }
        ?>
    </section>
</div>
</body>
</html>
