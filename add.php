<!-- Code to insert item data -->
<?php
include('auth.php');
include 'config.php';

// Check connection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required fields are set
    if (isset($_POST["item-name"], $_POST["item-quantity"], $_POST["item-price"])) {
        $item_name = $_POST["item-name"];
        $quantity = $_POST["item-quantity"];
        $price = $_POST["item-price"];

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO items (item_name, quantity, price) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $item_name, $quantity, $price);  // Use "sdi" for string, double, integer types

        if ($stmt->execute()) {
            $successMessage = 'Item added successfully';
        } else {
            $successMessage = 'Error adding item: ' . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        $successMessage = 'Please fill in all the required fields';
    }
}



// Close the database connection
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
</head>
<body>
    <div id="sidebar">
        <header>
            <img src="assets/addlogo.png" alt="Company Logo">
            <a href="start.php"> <h1>Menu</h1> </a>
        </header>
<nav>


            <div class="nav-option"><a href="add.php" class="nav-button" style="background-color: #3498db;">Add Item</a></div>
            <div class="nav-option"><a href="issue.php" class="nav-button">Issue Item</a></div>
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
    <h2>Add Item</h2>
    <form id="item-form" method="post" action="add.php" enctype="multipart/form-data">
        
        <div class="form-field"div style="display: flex; ">
       
        <label for="item-name" style="font-size: 22px;">Item Name:</label>
        <select id="item-name" name="item-name" required>
            <option value="" disabled selected>Select an item</option>
            <?php
            $itemNames = array("Desktop","Monitor", "Keyboard", "Mouse", "Speakers", "D Link Switch", "LAN Tester", "RJ 45 Connector", "TP Link Switch", "Keyboard+mouse(combo)", "CAT-6 networking cable(patch cord)", "crimping tool", "UPS 1kv APC", "Motherboard", "HDMI Cable", "HDMI Switch",
            "Dlink 24port Switch", "Dlink 16port Switch", "Dlink 5port Switch", "TP Link Router", "SMPS", "CPU Fan", "VGA Cable", "Cable cab 6", "Tech Rmte 24 port switch");
            foreach ($itemNames as $itemName) {
                echo '<option value="' . $itemName . '">' . $itemName . '</option>';
            }
            ?>
           <!--  <option value="Other">Other (Please specify)</option> -->
        </select>

        <div class="form-field" id="custom-item-container" style="display: none;">
        <label for="custom-item-name" style="font-size: 22px;">Custom Item Name:</label>
        <input type="text" id="custom-item-name" name="custom-item-name">
    </div>
   
                <label for="item-brand"style="font-size: 22px;">Brand:</label>
                <input type="text" id="item-brand" name="item-brand">
  
                <label for="item-specs"style="font-size: 22px;">Specifications:</label>
                <textarea id="item-specs" name="item-specs" rows="4"></textarea>

                </div>
                <br>
                <br>
        <div class="form-field"div style="display: flex; ">
 
                <label for="item-quantity"style="font-size: 22px;">Quantity:</label>
                <input type="number" id="item-quantity" name="item-quantity" required>
            
   
            <label for="item-price"style="font-size: 22px;">Price:</label>
            <input type="number" id="item-price" name="item-price" required>
   
            <label for="item-image"style="font-size: 22px;">Invoice Image:</label>
            <input type="file" id="item-image" name="item-image">
        </div>
               
        <br>
        <br>
  
                <div class="form-field">
                    <button type="submit" class="solid-button" style=" font-size: 22px;">Add</button>
                    </div>
            </form>


  <script>
function handleItemNameChange(selectElement) {
    const customItemContainer = document.getElementById('custom-item-container');
    const customItemNameInput = document.getElementById('custom-item-name');
    
    if (selectElement.value === 'Other') {
        customItemContainer.style.display = 'block';
        customItemNameInput.setAttribute('required', 'required');
    } else {
        customItemContainer.style.display = 'none';
        customItemNameInput.removeAttribute('required');
    }
}
</script>



            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($successMessage)) {
                echo '<div id="popup" class="popup-message">';
                echo '<div>' . htmlspecialchars($successMessage) . '</div>';
                echo '</div>';
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
$sql = "SELECT item_name, quantity, CONCAT('₹', price) AS price ,date_added
        FROM items 
        ORDER BY id DESC 
        LIMIT 5";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>DateTime</th>
            </tr>";

    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["item_name"]."</td>
                <td>".$row["quantity"]."</td>
                <td>".$row["price"]."</td>
                <td>".$row["date_added"]."</td>

              
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}


// Close the database connection
$conn->close();
?>    
        
        </section>

 

    <footer>
        <!-- Footer content here -->
    </footer>
    <script>
        // Function to close the popup after a few seconds
        function closePopup() {
            var popup = document.getElementById('popup');
            popup.style.display = 'none';
        }

        // Display the popup for 5 seconds
        setTimeout(closePopup, 5000);
    </script>
</body>
</html>