<?php
include('auth.php');
include 'config.php';

// Initialize selected item variable
$selectedItem = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["item-name"])) {
    $selectedItem = $_POST["item-name"]; // Get the selected item from the form

    // Fetch available stock for the selected item from the database
    $sql = "SELECT item_name, quantity_available FROM inventroy WHERE item_name = '$selectedItem'";
    $result = $conn->query($sql);
}

// Fetch items with quantities below 10
$sql_low_quantity = "SELECT item_name, quantity_available FROM inventroy WHERE quantity_available < 10";
$result_low_quantity = $conn->query($sql_low_quantity);

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS file -->
</head>
<body>
<div id="sidebar">
        <header>
            <img src="assets/stockimg.png" alt="Company Logo">
            <h1>Menu</h1>
        </header>
        <nav>
            <div class="nav-option"><a href="add.php" class="nav-button">Add Item</a></div>
            <div class="nav-option"><a href="issue.php" class="nav-button">Issue Item</a></div>
            <div class="nav-option"><a href="desktop.php" class="nav-button">Issue Desktop</a></div>
            <div class="nav-option"><a href="stock.php" class="nav-button" style="background-color: #3498db;">Stock</a></div>
            <div class="nav-option"><a href="search.php" class="nav-button">Search</a></div>
            <div class="nav-option"><a href="scrap.php" class="nav-button">Scrap</a></div>
            <div class="nav-option"><a href="report.html" class="nav-button">Report</a></div>
            <div class="nav-option"><a href="chat.php" class="nav-button">Chat</a></div>
            <div class="nav-option"><a href="login/register.php" class="nav-button">Register</a></div>
        </nav>
    </div>
    <div id="content">
        <header style="background-color: #979292;">
            <img src="assets/imgcl.png" alt="Company Logo">
        </header>
        <header>Stocks Available</header>
        <section id="search-form">
            <form method="post" action="">
                <label for="item-name">Select an Item:</label>
                <select id="item-name" name="item-name">
                    <option value="" disabled <?php if (empty($selectedItem)) echo "selected"; ?>>Select an item</option>
                    <?php
                    $itemNames = array(
                        "Monitor", "Keyboard", "Mouse", "Speakers", "D Link Switch",
                        "LAN Tester", "RJ 45 Connector", "TP Link Switch",
                        "Keyboard+mouse(combo)", "CAT-6 networking cable(patch cord)",
                        "crimping tool", "UPS 1kv APC", "Motherboard", "HDMI Cable",
                        "HDMI Switch", "Dlink 24port Switch", "Dlink 16port Switch",
                        "Dlink 5port Switch", "TP Link Router", "SMPS", "CPU Fan",
                        "VGA Cable", "Cable cab 6", "Tech Rmte 24 port switch"
                    );

                    foreach ($itemNames as $itemName) {
                        echo '<option value="' . $itemName . '"';
                        if ($selectedItem === $itemName) echo 'selected';
                        echo '>' . $itemName . '</option>';
                    }
                    ?>
                </select>
                <button type="submit" class="solid-button" style="font-size: 12px; background-color: #3498db;">Search</button>
            </form>
        </section>
        <section id="stocks-list">
            <?php
            if (!empty($selectedItem)) {
                if (isset($result) && $result->num_rows > 0) {
                    // Display the table here
                } else {
                    echo "<p>No items found matching your search.</p>";
                }
            } else {
                echo "<p>Please select an item.</p>";
            }
            ?>
                       <table>
                <tr>
                    <th>Item Name</th>
                    <th>Available Stock</th>
                </tr>
                <?php
                if (isset($result) && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["item_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["quantity_available"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No items found matching your search.</td></tr>";
                }
                ?>
            </table>
        </section>

        <section id="stocks-list">
            <header>Low Quantity Items</header>
            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Available Stock</th>
                </tr>
                <?php
                if (isset($result_low_quantity) && $result_low_quantity->num_rows > 0) {
                    while ($row = $result_low_quantity->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["item_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["quantity_available"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No items found with stock below 10.</td></tr>";
                }
                ?>
            </table>
        </section>
    </div>
</body>
</html>
