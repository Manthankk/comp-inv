<?php
include('auth.php');
include 'config.php';

$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $item_name = $_POST["product-name"];
    $department = $_POST["department-select"];
    $room = $_POST["room-select"];
    $quantity = $_POST["issued-quantity"];
    $receiver = $_POST["receiver"];
    $issuedBy = $_POST["issued-by"];

     

    // Check if the item is available in the requested quantity
    $quantity_available_sql = "SELECT quantity_available FROM inventroy WHERE item_name = '$item_name'";
    $quantity_available_result = $conn->query($quantity_available_sql);

    if ($quantity_available_result && $quantity_available_result->num_rows > 0) {
        $quantity_available_row = $quantity_available_result->fetch_assoc();
        $quantity_available = $quantity_available_row["quantity_available"];

        if ($quantity <= $quantity_available) {
            // Insert data into the issue table
            if ($item_name === "Desktop") {
                // Insert multiple rows for desktop components
                $desktop_components = array("Mouse", "Keyboard", "CPU", "Monitor");
                foreach ($desktop_components as $component) {
                    $insert_sql = "INSERT INTO issue (item_name, department, room, quantity, receiver, issued_by)
                                   VALUES ( '$component', '$department', '$room', '$quantity', '$receiver', '$issuedBy')";

                    if ($conn->query($insert_sql) !== TRUE) {
                        $successMessage = 'Error issuing item: ' . $conn->error;
                        break;
                    }
                }
            } else {
                // Insert a single row for other items
                $insert_sql = "INSERT INTO issue ( item_name, department, room, quantity, receiver, issued_by)
                               VALUES ( '$item_name', '$department', '$room', '$quantity', '$receiver', '$issuedBy')";

                if ($conn->query($insert_sql) === TRUE) {
                    // Update the inventory
                    $update_inventory_sql = "UPDATE inventroy
                        SET quantity_available = quantity_available - $quantity,
                            issued = issued + $quantity
                        WHERE item_name = '$item_name'";

                    if ($conn->query($update_inventory_sql) !== TRUE) {
                        $successMessage = 'Error updating inventory: ' . $conn->error;
                    } else {
                        $successMessage = 'Item issued successfully';
                    }
                } else {
                    $successMessage = 'Error issuing item: ' . $conn->error;
                }
            }
        } else {
            $successMessage = 'Requested quantity exceeds available quantity';
        }
    } else {
        $successMessage = 'Item not found in inventory';
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
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles.css">
    <!-- Add this to the head section of your HTML -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
            $(document).ready(function(){
                $("#issue-details-form").submit(function(event){
                    // Fetch the updated quantity available from the server
                    var item_name = $("#product-name").val();
                    var quantityAvailable;
                    $.ajax({
                        url: "get_quantity_available.php",
                        type: "POST",
                        data: {item_name: item_name},
                        async: false, // Ensure synchronous request to get the updated value
                        success: function(data){
                            quantityAvailable = parseInt(data);
                        },
                        error: function(xhr, status, error){
                            console.error("Error fetching quantity available: " + error);
                            quantityAvailable = 0; // Set a default value
                        }
                    });

                    var issuedQuantity = parseInt($("#issued-quantity").val());
                    
                    // Check if the issued quantity is greater than available quantity
                    if (issuedQuantity > quantityAvailable) {
                        alert("Cannot issue inventory as there is less stock.");
                        event.preventDefault(); // Prevent form submission
                    } else {
                        var remainingQuantity = quantityAvailable - issuedQuantity;
                        
                        // Check if the remaining quantity is less than 10
                        if (remainingQuantity < 10 && remainingQuantity >= 0) {
                            alert("Only " + remainingQuantity + " amount of this particular inventory is remaining.");
                        }
                    }
                });
            });


            $(document).ready(function(){
            $("#issue-details-form").submit(function(event){
                // Validate Issued Quantity
                var issuedQuantity = parseInt($("#issued-quantity").val());
                if (issuedQuantity <= 0 || isNaN(issuedQuantity)) {
                    alert("Issued quantity must be a positive number.");
                    event.preventDefault(); // Prevent form submission
                }

                // Validate Issued By and Receiver
                var issuedBy = $("#issued-by").val();
                var receiver = $("#receiver").val();
                var nameRegex = /^[a-zA-Z\s]+$/; // Regular expression for alphabets and spaces
                if (!nameRegex.test(issuedBy) || !nameRegex.test(receiver)) {
                    alert("Issued By and Receiver must contain only alphabets and spaces.");
                    event.preventDefault(); // Prevent form submission
                }
            });
        });
        </script>
</head>
<body>
    <div id="sidebar">
        <header>
            <img src="assets/giving_4117258.png" alt="Company Logo">
            <a href="start.php"> <h1>Menu</h1> </a>
        </header>
        <nav>


<div class="nav-option"><a href="add.php" class="nav-button" >Add Item</a></div>
<div class="nav-option"><a href="issue.php" class="nav-button"style="background-color: #3498db;">Issue Item</a></div>
<div class="nav-option"><a href="desktop.php" class="nav-button">Issue Desktop</a></div>
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
        <section id="add-item" style="padding: 20px;">
    <h2>Issue Item</h2>
    
    <form id="issue-details-form" method="post" action="">
    <div class="form-field" style="display: flex; align-items: center;">
    <!--     <label for="current-date" style="font-size: 22px;">Date:</label>
        <input type="date" id="current-date" name="current-date" required> -->
        
        <label for="item-name" style="font-size: 22px; margin-left: 20px;">Item Name:</label>
        <select id="product-name" name="product-name" required>
            <option value="" disabled selected>Select an item</option>
            <?php
            $itemNames = array("D Link Switch", "LAN Tester", "RJ 45 Connector", "TP Link Switch", "CAT-6 networking cable(patch cord)", "crimping tool", "UPS 1kv APC", "Motherboard", "HDMI Cable", "HDMI Switch",
            "Dlink 24port Switch", "Dlink 16port Switch", "Dlink 5port Switch", "TP Link Router", "SMPS", "CPU Fan", "VGA Cable", "Cable cab 6", "Tech Rmte 24 port switch");
            foreach ($itemNames as $itemName) {
                echo '<option value="' . $itemName . '">' . $itemName . '</option>';
            }
            ?>
            <option value="Other">Other (Please specify)</option>
        </select>
        
        <label for="department" style="font-size: 22px; margin-left: 20px;">Department:</label>
<select name="department-select" id="department-select" required>
    <option value="" disabled selected>Select a department</option>
    <option value="Applied science">Applied Science</option>
    <option value="Computer Engineering">Computer Engineering</option>
    <option value="Mechanical Engineering">Mechanical Engineering</option>
    <option value="IT">IT</option>
    <option value="EXTC">EXTC</option>
    <option value="AIDS">AIDS</option>
    <option value="Accounts">Accounts</option>
    <option value="Libary">Libary</option>
    
    <!-- Add more departments as needed -->
</select>

<label for="room" style="font-size: 22px; margin-left: 20px;">Room:</label>
<select name="room-select" id="room-select">
    <option value="none">none</option>
    <option value="A1">A1</option>
    <option value="A2">A2</option>
    <option value="A3">A3</option>
    <option value="A4">A4</option>
    <option value="A5">A5</option>
    <option value="C1">C1</option>
    <option value="C2">C2</option>
    <option value="C3">C3</option>
    <option value="C4">C4</option>
    <option value="C5">C5</option>
    
</select>

    </div>
    <br>
    <br>
    <div class="form-field" style="display: flex; align-items: center;">
        <label for="issued-quantity" style="font-size: 22px;">Issued Quantity:</label>
        <input type="number" id="issued-quantity" name="issued-quantity" required>
        
        <label for="receiver" style="font-size: 22px; margin-left: 20px;">Receiver:</label>
        <input type="text" id="receiver" name="receiver" required>
        
        <label for="issued-by" style="font-size: 22px; margin-left: 20px;">Issued By:</label>
        <input type="text" id="issued-by" name="issued-by" required>
    </div>
    <br>
    <br>
    <div class="form-field">
        <button type="submit" class="solid-button" style="font-size: 22px;">Issue Item</button>
    </div>
</form>



    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($successMessage)) {
        echo '<div id="popup" class="popup-message">';
        echo '<div>' . htmlspecialchars($successMessage) . '</div>';
        echo '</div>';
    }
}

    ?>

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

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["date"] . "</td>
                    <td>" . $row["item_name"] . "</td>
                    <td>" . $row["department"] . "</td>
                    <td>" . $row["quantity"] . "</td>
                    <td>" . $row["receiver"] . "</td>
                    <td>" . $row["issued_by"] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }
    ?>



</body>
</html>
