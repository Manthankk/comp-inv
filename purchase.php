<?php
include('auth.php');
include 'config.php';

// Initialize date range variables
$fromDate = '';
$toDate = '';

// Check if the form has been submitted with date range values
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fromDate = $_POST["from-date"];
    $toDate = $_POST["to-date"];

    // Check if the selected dates are in the future
    $today = date("Y-m-d");
    if ($fromDate > $today || $toDate > $today) {
        echo "<script>alert('I haven't found any time machine yet!');</script>";
    } else {
        // Fetch purchased items within the selected date range from the database
        $sql = "SELECT item_name, company_name, specification, quantity, price, date_added FROM items WHERE date_added BETWEEN '$fromDate' AND '$toDate'";
        $result = $conn->query($sql);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchased Items Report</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS file -->
</head>
<body>
<div id="sidebar">
    <header>
        <img src="assets/reportimg.png" alt="Company Logo">
        <h1>Menu</h1>
    </header>
    <nav>
        <div class="nav-option"><a href="add.php" class="nav-button">Add Item</a></div>
        <div class="nav-option"><a href="issue.php" class="nav-button">Issue Item</a></div>
        <div class="nav-option"><a href="desktop.php" class="nav-button">Issue Desktop</a></div>
        <div class="nav-option"><a href="stock.php" class="nav-button">Stock</a></div>
        <div class="nav-option">
            <a href="search.php" class="nav-button">Search</a>
        </div>
        <div class="nav-option"><a href="scrap.php" class="nav-button">Scrap</a></div>
        <div class="nav-option"><a href="purchase.php" class="nav-button" style="background-color: #3498db;">Purchase Report</a></div>
        <div class="nav-option"><a href="chat.php" class="nav-button">Chat</a></div>
        <div class="nav-option"><a href="login/register.php" class="nav-button">Register</a></div>
        <div class="nav-option"><a href="report.html" class="nav-button" style="background-color: #45a049;">Back</a></div>
    </nav>
</div>
<div id="content">
    <header style="background-color: #979292;">
        <img src="assets/imgcl.png" alt="Company Logo">
    </header>
    <header>Purchased Items Report</header>
    <section id="date-range-filter">
        <form method="post" action="">
            <label for="from-date" style="font-size: 20px;">From:</label>
            <input type="date" id="from-date" name="from-date" value="<?php echo $fromDate; ?>" max="<?php echo date('Y-m-d'); ?>" required>
            <label for="to-date" style="font-size: 20px;">To:</label>
            <input type="date" id="to-date" name="to-date" value="<?php echo $toDate; ?>" max="<?php echo date('Y-m-d'); ?>" required>
            <button type="submit" class="solid-button" style="font-size: 12px; background-color: #3498db;">Filter</button>
        </form>
    </section>
    <section id="purchased-items-list">
        <?php
        if (isset($result) && $result->num_rows > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>Item Name</th>";
            echo "<th>Company Name</th>";
            echo "<th>Specification</th>";
            echo "<th>Quantity</th>";
            echo "<th>Price</th>";
            echo "<th>Date Added</th>";
            echo "</tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["item_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["company_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["specification"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["date_added"]) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo '<button id="downloadPdfButton" class="solid-button" style="font-size: 12px; background-color: #3498db;">Download PDF</button>';
        } else {
            echo "<p>No purchased items found within the selected date range.</p>";
        }
        ?>
    </section>
</div>
<script src="script.js"></script>
<script>
document.getElementById("downloadPdfButton").addEventListener("click", function() {
    var fromDate = document.getElementById("from-date").value;
    var toDate = document.getElementById("to-date").value;

    // Make an AJAX request to your server to generate the PDF with start and end dates
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "generate_pdf.php?fromDate=" + fromDate + "&toDate=" + toDate, true);
    xhr.responseType = "blob";

    xhr.onload = function() {
        if (xhr.status === 200) {
            // Create a blob object with the PDF data
            var blob = new Blob([xhr.response], { type: "application/pdf" });

            // Create a link element to trigger the download
            var link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = "Purchased_Items_Report.pdf";
            link.style.display = "none";

            // Trigger a click event on the link to start the download
            document.body.appendChild(link);
            link.click();

            // Clean up
            document.body.removeChild(link);
        }
    };
    xhr.send();
});
</script>
</body>
</html>
