<?php
include('auth.php');
include 'config.php';

// Initialize variables
$filterItemName = isset($_GET["filter-item-name"]) ? $_GET["filter-item-name"] : '';
$filterDepartment = isset($_GET["filter-department"]) ? $_GET["filter-department"] : '';
$fromDate = isset($_GET["from-date"]) ? $_GET["from-date"] : '';
$toDate = isset($_GET["to-date"]) ? $_GET["to-date"] : '';
$totalQuantity = 0; // Initialize total quantity

// Check if the form has been submitted with values
if (!empty($filterItemName) && !empty($filterDepartment) && !empty($fromDate) && !empty($toDate)) {
    // Fetch total quantity issued to the department for the item during the date range
    $sql = "SELECT SUM(quantity) AS total_quantity FROM issue WHERE item_name = '$filterItemName' AND department = '$filterDepartment' AND date BETWEEN '$fromDate' AND '$toDate'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalQuantity = $row["total_quantity"];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Quantity Issued</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS file -->
</head>
<body>
<script src="script.js"></script>

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
        <div class="nav-option"><a href="search.php" class="nav-button">Search</a></div>
        <div class="nav-option"><a href="scrap.php" class="nav-button">Scrap</a></div>
        <div class="nav-option"><a href="issue_report.php" class="nav-button" style="background-color: #3498db;">Issue Report</a></div>
        <div class="nav-option"><a href="chat.php" class="nav-button">Chat</a></div>
        <div class="nav-option"><a href="login/register.php" class="nav-button">Register</a></div>
        <div class="nav-option"><a href="report.html" class="nav-button" style="background-color: #45a049;">Back</a></div>
    </nav>
</div>

<div id="content">
    <header style="background-color: #979292;">
        <img src="assets/imgcl.png" alt="Company Logo">
    </header>
    <header>Total Quantity Issued</header>

    <section id="date-range-filter">
        <form id="filter-form" method="get" action="issue_quantity_report.php">
            <label for="filter-item-name">Item Name:</label>
            <select id="filter-item-name" name="filter-item-name">
                <option value="" disabled selected>Select an item</option>
                <?php
                $itemNames = array("Desktop","Monitor", "Keyboard", "Mouse", "Speakers", "D Link Switch", "LAN Tester", "RJ 45 Connector", "TP Link Switch", "Keyboard+mouse(combo)", "CAT-6 networking cable(patch cord)", "crimping tool", "UPS 1kv APC", "Motherboard", "HDMI Cable", "HDMI Switch",
                "Dlink 24port Switch", "Dlink 16port Switch", "Dlink 5port Switch", "TP Link Router", "SMPS", "CPU Fan", "VGA Cable", "Cable cab 6", "Tech Rmte 24 port switch");
                foreach ($itemNames as $itemName) {
                    echo '<option value="' . $itemName . '">' . $itemName . '</option>';
                }
                ?>
                <option value="Other">Other (Please specify)</option>
            </select>

            <label for="filter-department">Department:</label>
            <select id="filter-department" name="filter-department">
                <option value="" disabled selected>Select a department</option>
                <option value="Applied Science">Applied Science</option>
                <option value="Computer Engineering">Computer Engineering</option>
                <option value="Mechanical Engineering">Mechanical Engineering</option>
                <option value="IT">IT</option>
                <option value="EXTC">EXTC</option>
                <option value="AIDS">AIDS</option>
                <option value="Accounts">Accounts</option>
                <option value="Library">Library</option>
            </select>

            <label for="from-date">From:</label>
            <input type="date" id="from-date" name="from-date" required>

            <label for="to-date">To:</label>
            <input type="date" id="to-date" name="to-date" required>

            <button type="submit" class="solid-button" style="font-size: 12px; background-color: #3498db;">Filter</button>
        </form>
    </section>
    <section id="total-quantity">
        <?php
        if ($totalQuantity > 0) {
            echo "<p>Total quantity of $filterItemName issued to $filterDepartment from $fromDate to $toDate is: $totalQuantity</p>";
        } else {
            echo "<p>No records found for the selected criteria.</p>";
        }
        ?>
    </section>
</div>
</body>
</html>





