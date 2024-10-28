<?php
include('auth.php');
include 'config.php';

$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $item_name = $_POST["product-name"];
    $department = $_POST["select-department"];
    $receiver = $_POST["receiver"];
    $issuedBy = $_POST["issued-by"];
    $quantity = $_POST["issued-quantity"];
    $desktop_ids = $_POST["desktop-id"];
    $room = $_POST["room"];

    foreach ($desktop_ids as $desktop_id) {
        // Check if the item is available in the requested quantity
        $quantity_available_sql = "SELECT quantity_available FROM inventroy WHERE item_name = '$item_name'";
        $quantity_available_result = $conn->query($quantity_available_sql);

        if ($quantity_available_result && $quantity_available_result->num_rows > 0) {
            $quantity_available_row = $quantity_available_result->fetch_assoc();
            $quantity_available = $quantity_available_row["quantity_available"];

            if ($quantity <= $quantity_available) {
                // Insert data into the issue table
                $insert_sql = "INSERT INTO issue (item_name, department, quantity, receiver, issued_by, desktop_id, room)
                               VALUES ('$item_name', '$department', '$quantity', '$receiver', '$issuedBy', '$desktop_id', '$room')";

                if ($conn->query($insert_sql) === TRUE) {
                    // Update the inventory
                    $update_inventory_sql = "UPDATE inventroy
                                             SET quantity_available = quantity_available - $quantity,
                                                 issued = issued + $quantity
                                             WHERE item_name = '$item_name'";

                    if ($conn->query($update_inventory_sql) === TRUE) {
                        $successMessage = 'Item issued successfully';
                    } else {
                        $successMessage = 'Error updating inventory: ' . $conn->error;
                    }
                } else {
                    $successMessage = 'Error issuing item: ' . $conn->error;
                }
            } else {
                $successMessage = 'Requested quantity exceeds available quantity';
            }
        } else {
            $successMessage = 'Item not found in inventory';
        }
    }
}

// Fetch issued details
$sql = "SELECT date, item_name, department, quantity, receiver, issued_by
        FROM issue 
        ORDER BY id DESC 
        LIMIT 4";

$result = $conn->query($sql);

$conn->close();
?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desktop Issuance</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        #issue-details-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .form-field {
            flex-basis: calc(33.33% - 20px);
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }
    </style
</head>
<body>
    <div id="sidebar">
        <header>
            <img src="assets/giving_4117258.png" alt="Company Logo">
            <a href="start.php"> <h1>Menu</h1> </a>
        </header>
        <nav>


<div class="nav-option"><a href="add.php" class="nav-button" >Add Item</a></div>
<div class="nav-option"><a href="issue.php" class="nav-button">Issue Item</a></div>
<div class="nav-option"><a href="desktop.php" class="nav-button"style="background-color: #3498db;">Issue Desktop</a></div>
<div class="nav-option"><a href="stock.php" class="nav-button">Stock</a></div>
<div class="nav-option">
    <a href="search.php" class="nav-button">Search</a>
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
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($successMessage)) {
                echo '<div id="popup" class="popup-message">';
                echo '<div>' . htmlspecialchars($successMessage) . '</div>';
                echo '</div>';
            }
            ?>
            <h2>Issue Desktop</h2>
            <form id="issue-details-form" method="post" action="">
            <div class="form-field">
                <label for="product-name" style="font-size: 22px;">Item Name:</label>
                <select id="product-name" name="product-name" required>
                    <option value="" disabled selected>Select an item</option>
                    <?php
                    $itemNames = array("Monitor", "Keyboard", "Mouse", "Speakers",  "Keyboard+mouse(combo)", "DESKTOP", "CPU");
                    foreach ($itemNames as $itemName) {
                        echo '<option value="' . $itemName . '">' . $itemName . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-field">
                <label for="select-department" style="font-size: 22px;">Department:</label>
                <select name="select-department" id="select-department" required>
                    <option value="" disabled selected>Select a department</option>
                    <option value="Applied science">Applied Science</option>
                    <option value="Computer Engineering">Computer Engineering</option>
                    <option value="Mechanical Engineering">Mechanical Engineering</option>
                    <option value="IT">IT</option>
                    <option value="EXTC">EXTC</option>
                    <option value="AIDS">AIDS</option>
                    <option value="Accounts">Accounts</option>
                    <option value="Library">Library</option>
                </select>
            </div>
            <div class="form-field">
                <label for="room" style="font-size: 22px;">Room:</label>
                <select name="room" id="room">
                    <option>A1</option>
                    <option>A2</option>
                    <option>A3</option>
                    <option>A4</option>
                    <option>A5</option>
                    <option>C1</option>
                    <option>C2</option>
                    <option>C3</option>
                </select>
            </div>
            <div class="form-field" id="desktop-ids-container">
                    <label for="desktop-id" style="font-size: 22px;">Desktop ID:</label>
                    <input type="text" id="desktop-id" name="desktop-id[]" placeholder="Enter Desktop ID">
                    <button type="button" id="add-desktop-id">Add</button>
                </div>
            <div class="form-field">
                <label for="issued-quantity" style="font-size: 22px;">Issued Quantity:</label>
                <input type="number" id="issued-quantity" name="issued-quantity" value="1" readonly>
            </div>
            <div class="form-field">
                <label for="receiver" style="font-size: 22px;">Receiver:</label>
                <input type="text" id="receiver" name="receiver" required>
            </div>
            <div class="form-field">
                <label for="issued-by" style="font-size: 22px;">Issued By:</label>
                <input type="text" id="issued-by" name="issued-by" required>
            </div>
            <div class="form-field">
                <button type="submit" class="solid-button" style="font-size: 22px;">Issue Item</button>
            </div>
        </form>
            <!-- Issued Details Table -->
            <h2>Issued Details</h2>
            <?php
            if ($result->num_rows > 0) {
                echo "<table>
                        <tr>
                            <th>Date</th>
                            <th>Item name</th>
                            <th>Department</th>
                            <th>Quantity</th>
                            <th>Receiver</th>
                            <th>Issued By</th>
                        </tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["date"]."</td>
                            <td>".$row["item_name"]."</td>
                            <td>".$row["department"]."</td>
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
<footer>
    <!-- Footer content... -->
</footer>
</div>

<script>
// JavaScript code to dynamically add Desktop ID input fields
document.addEventListener('DOMContentLoaded', function() {
    const addDesktopIdButton = document.getElementById('add-desktop-id');
    const desktopIdsContainer = document.getElementById('desktop-ids-container');
    const maxDesktopIds = 5;
    let desktopIdCount = 0;

    addDesktopIdButton.addEventListener('click', function() {
        if (desktopIdCount < maxDesktopIds) {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'desktop-id[]'; // Use array notation for multiple values
            input.placeholder = 'Enter Desktop ID';
            desktopIdsContainer.appendChild(input);
            desktopIdCount++;
        }
    });
});
</script>
</body>
</html>