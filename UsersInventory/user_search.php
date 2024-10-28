

<?php


session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $username = $_SESSION["username"];
    $branch = isset($_SESSION["branch"]) ? $_SESSION["branch"] : "N/A"; // Default to "N/A" if not set
} else {
    // Redirect to login if not logged in
    header("Location: ./login.php");
    exit;
}
?>
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

$successMessage = '';

// Check if the filter form is submitted
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
    $result = null;
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
</head>
<body>
    <div id="sidebar">
    <header>
            <img src="../assets/development.png" alt="Company Logo">
           <a href="user_start.php"><h1>Menu</h1></a> 
        
    </header>
        <nav>
        <div class="nav-option">
                <a href="user_search.php" class="nav-button" style="background-color: #3498db;">Search</a>
            </div>
          <!--   <div class="nav-option"><a href="scrap.php" class="nav-button">Scrap</a></div>

            <div class="nav-option"><a href="report.html" class="nav-button">Report</a></div> -->
            <div class="nav-option"><a href="#" class="nav-button">Chat</a></div>
        </nav>

        
     
    </div>
    <div id="content">
        <header style="background-color: #8b8989;">
            <img src="../assets/imgcl.png" alt="Company Logo">
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
                    
                    <option value="<?php echo htmlspecialchars($branch); ?>"><?php echo htmlspecialchars($branch); ?></option>
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

                <label for="filter-room">Room:</label>
                <select id="filter-room" name="filter-room">
                    <option value="none">none</option>
                    <option value="AO1">AO1</option>
                    <option value="AO2">AO2</option>
                    <option value="AO3">AO3</option>
                    <option value="AO4">AO4</option>
                    <option value="AO5">AO5</option>
                    <option value="CO1">CO1</option>
                    <option value="CO2">CO2</option>
                    <option value="CO3">CO3</option>
                    <option value="CO4">CO4</option>
                    <option value="CO5">CO5</option>
                </select>

                <label for="filter-desktop-id">Desktop ID:</label>
                <input type="text" id="filter-desktop-id" name="filter-desktop-id">

                <label for="filter-item-id">Item ID:</label>
                <input type="text" id="filter-item-id" name="filter-item-id">
                <br>
                <br>

                <button type="submit" name="apply-filters" class="solid-button" style="font-size: 22px;">Apply Filters</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["apply-filters"])) {
                echo '<div id="popup" class="popup-message">';
                echo '<div>Filters Applied</div>';
                echo '</div>';

                if ($result->num_rows > 0) {
                    echo "<h2>Issued Details</h2>";
                    // Output the filter form here if you want
                    // ... (your filter form code)
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
            }
            ?>
        </section>
        <footer>
            <!-- Footer content... -->
        </footer>
    </div>
</body>
</html>
