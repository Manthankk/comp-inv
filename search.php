<?php
include('auth.php');
include 'config.php';

$successMessage = '';

// Check if the filter form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["apply-filters"])) {
    // Fetch issued details with filters
    $filter_department = isset($_GET["filter-department"]) ? $_GET["filter-department"] : "";
    $filter_item_name = isset($_GET["filter-item-name"]) ? $_GET["filter-item-name"] : "";
    $filter_room = isset($_GET["filter-room"]) ? $_GET["filter-room"] : "";
    $filter_desktop_id = isset($_GET["filter-desktop-id"]) ? $_GET["filter-desktop-id"] : "";
    $filter_item_id = isset($_GET["filter-item-id"]) ? $_GET["filter-item-id"] : "";

    // Modify your SQL query to include the filters
    $sql = "SELECT id, date, item_name, department, room, desktop_ID, quantity, receiver, issued_by
    FROM issue 
    WHERE (department = '$filter_department' OR '$filter_department' = 'all' OR '$filter_department' = '')
      AND (item_name = '$filter_item_name' OR '$filter_item_name' = 'all' OR '$filter_item_name' = '')
      AND (room = '$filter_room' OR '$filter_room' = 'all' OR '$filter_room' = 'none' OR '$filter_room' = '')
      AND (desktop_ID = '$filter_desktop_id' OR '$filter_desktop_id' = 'all' OR '$filter_desktop_id' = '')
      AND (id = '$filter_item_id' OR '$filter_item_id' = 'all' OR '$filter_item_id' = '')
      ORDER BY id DESC";




    $result = $conn->query($sql);
/*     echo "Debug: SQL Query: $sql";
    echo "Debug: Filter Department: $filter_department, Item Name: $filter_item_name, Room: $filter_room, Desktop ID: $filter_desktop_id, Item ID: $filter_item_id"; */


} else {
    // Fetch issued details without filters
    $sql = "SELECT id, date, item_name, department, room, desktop_ID, quantity, receiver, issued_by
            FROM issue 
            ORDER BY id DESC 
            LIMIT 4";

    $result = $conn->query($sql);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Issuance</title>
    <link rel="stylesheet" href="styles.css">
    <style>
      /* CSS for Search Page */

/* Style for form elements */
/* CSS for Search Page */

/* Style for form elements */
#filter-form label {
    display: inline-block; /* Display labels inline */
    margin-bottom: 5px;
    width: 150px; /* Adjusted width for labels to fit "Desktop ID" */
}

#filter-form select,
#filter-form input[type="text"] {
    display: inline-block; /* Display elements inline */
    width: auto; /* Allow elements to take up necessary space */
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Ensure padding and border are included in width */
    font-size: 14px;
}

#filter-form button {
    display: inline-block; /* Display button inline */
    padding: 8px;
    background-color: #3498db;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 8px;
}

/* Ensure horizontal scroll bar when needed */
#content {
    overflow-x: auto;
}

#issue-form h2 {
    font-size: 20px;
    font-weight: bold;
    text-transform: uppercase;
    color: #333; /* Adjust color as needed */
    margin-bottom: 15px;
    border-bottom: 2px solid #3498db; /* Add a bottom border */
    padding-bottom: 8px; /* Add padding to bottom */
}

    </style>
</head>
<body>
    <div id="sidebar">
    <header>
            <img src="assets/searchimg.png" alt="Company Logo">
            <a href="start.php"> <h1>Menu</h1> </a>
        
    </header>
    <nav>
            <div class="nav-option"><a href="add.php" class="nav-button">Add Item</a></div>
            <div class="nav-option"><a href="issue.php" class="nav-button">Issue Item</a></div>
            <div class="nav-option"><a href="desktop.php" class="nav-button">Issue Desktop</a></div>
            <div class="nav-option"><a href="stock.php" class="nav-button">Stock</a></div>
            <div class="nav-option">
                <a href="search.php" class="nav-button"style="background-color: #3498db;">Search</a>
            </div>
            <div class="nav-option"><a href="scrap.php" class="nav-button">Scrap</a></div>

            <div class="nav-option"><a href="report.html" class="nav-button">Report</a></div>
            <div class="nav-option"><a href="chat.php" class="nav-button">Chat</a></div>
            <div class="nav-option"><a href="login/register.php" class="nav-button">Register</a></div>
        </nav>

        
     
    </div>
    <div id="content">
        <header style="background-color: #8b8989;">
            <img src="assets/imgcl.png" alt="Company Logo">
        </header>
        <section id="issue-form" style="padding: 20px;">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["apply-filters"])) {
                echo '<div id="popup" class="popup-message">';
                echo '<div>Filters Applied</div>';
                echo '</div>';
            }
            ?>

            <h2>Issued Details</h2>

            <!-- Add Filter Form -->
            <form id="filter-form" method="get" action="">
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
                <br>

                <label for="filter-room">Room:</label>
                <select id="filter-room" name="filter-room">
                    <option value="none">none</option>
                    <option value="A1">A1</option>
                    <option value="A02">A2</option>
                    <option value="A3">A3</option>
                    <option value="A4">A4</option>
                    <option value="A5">A5</option>
                    <option value="C1">C1</option>
                    <option value="C2">C2</option>
                    <option value="C3">C3</option>
                    <option value="C4">C4</option>
                    <option value="C5">C5</option>
                </select>

                <label for="filter-desktop-id">Desktop ID:</label>
                <input type="text" id="filter-desktop-id" name="filter-desktop-id">
                

                <label for="filter-item-id">Item ID:</label>
                <input type="text" id="filter-item-id" name="filter-item-id">
                <br><br>

                <button type="submit" name="apply-filters" class="solid-button" style="font-size: 22px;">Apply Filters</button>
            </form>

            <?php
            if ($result->num_rows > 0) {
                echo "<table>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Item name</th>
                            <th>Department</th>
                            <th>Room</th>
                            <th>Desktop ID</th>
                            <th>Quantity</th>
                            <th>Receiver</th>
                            <th>Issued By</th>
                        </tr>";

                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["id"]."</td>
                            <td>".$row["date"]."</td>
                            <td>".$row["item_name"]."</td>
                            <td>".$row["department"]."</td>
                            <td>".$row["room"]."</td>
                            <td>".$row["desktop_ID"]."</td>
                            <td>".$row["quantity"]."</td>
                            <td>".$row["receiver"]."</td>
                            <td>".$row["issued_by"]."</td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "No results found.";
            }
            ?>
        </section>
        <script>
                // Function to allow only numeric input
                function restrictToNumeric(event) {
                    const keyCode = event.which ? event.which : event.keyCode;
                    const isValid = (keyCode >= 48 && keyCode <= 57) || keyCode === 8;
                    return isValid;
                }

                // Apply numeric validation to input fields
                document.addEventListener("DOMContentLoaded", function() {
                    const desktopIdInput = document.getElementById("filter-desktop-id");
                    const itemIdInput = document.getElementById("filter-item-id");

                    desktopIdInput.addEventListener("keypress", function(event) {
                        if (!restrictToNumeric(event)) {
                            event.preventDefault();
                        }
                    });

                    itemIdInput.addEventListener("keypress", function(event) {
                        if (!restrictToNumeric(event)) {
                            event.preventDefault();
                        }
                    });
                });
            </script>
        <footer>
            <!-- Footer content... -->
        </footer>
    </div>
</body>
</html>
