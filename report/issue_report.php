<?php
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

// Initialize date range variables
$fromDate = '';
$toDate = '';

// Check if the form has been submitted with date range values
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fromDate = $_POST["from-date"];
    $toDate = $_POST["to-date"];

    // Fetch issued items within the selected date range from the database
    $sql = "SELECT date, item_name, department, quantity, receiver, issued_by FROM issue WHERE date BETWEEN '$fromDate' AND '$toDate'";
    $result = $conn->query($sql);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issued Items Report</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS file -->









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
        <div class="nav-option"><a href="report.html" class="nav-button" style="background-color: #45a049;"><-BACK</a></div>
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
    <label for="from-date" style="font-size: 20px;">From:</label>
    <input type="date" id="from-date" name="from-date" value="<?php echo $fromDate; ?>" required>
    <label for="to-date" style="font-size: 20px;">To:</label>
    <input type="date" id="to-date" name="to-date" value="<?php echo $toDate; ?>" required>
    <!-- Move the hidden input fields here -->
    <input type="hidden" id="hidden-from-date" name="hidden-from-date" value="<?php echo $fromDate; ?>">
    <input type="hidden" id="hidden-to-date" name="hidden-to-date" value="<?php echo $toDate; ?>">
    <button type="submit" class="solid-button" style="font-size: 12px; background-color: #3498db;">Filter</button>
</form>

    </section>
    <section id="issued-items-list">
        <?php
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



        <button id="generatePdfButton"class="solid-button" style="font-size: 12px; background-color: #3498db;">Generate PDF</button>
        <script src="script.js"></script>
          
    </section>
   



</div>
<form>

</form>


</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("generatePdfButton").addEventListener("click", function() {
        console.log("Button clicked");

        var fromDate = document.getElementById("hidden-from-date").value;
        var toDate = document.getElementById("hidden-to-date").value;

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
                link.download = "Issued_Items_Report.pdf";
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
});
</script>

</body>
</html>
