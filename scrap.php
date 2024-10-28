<?php
include('auth.php');
include 'config.php';

$successMessage = '';

// Delete item from the "issue" table and add to "scrap" table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    $delete_id = $_POST["delete_id"];

    // Get user details
    $verified_by = $_POST["verified_by"];
    $specification = $_POST["specification"];

    // Retrieve details from the "issue" table before deletion
    $details_sql = "SELECT id, date, item_name, department, room, desktop_ID FROM issue WHERE id = $delete_id";
    $result_details = $conn->query($details_sql);

    if ($result_details->num_rows > 0) {
        $details_row = $result_details->fetch_assoc();

        // Add details to the "scrap" table
        $scrap_sql = "INSERT INTO scrap (item_name, department, room, desktop_ID, identification_no, verifyed_by, Specification)
        VALUES ('".$details_row["item_name"]."', '".$details_row["department"]."', '".$details_row["room"]."', '".$details_row["desktop_ID"]."', '".$details_row["id"]."', '$verified_by', '$specification')";

        
        if ($conn->query($scrap_sql) === TRUE) {
            // Delete item from the "issue" table
            $delete_sql = "DELETE FROM issue WHERE id = $delete_id";

            if ($conn->query($delete_sql) === TRUE) {
                $successMessage = 'Item scrapped successfully';
            } else {
                $successMessage = 'Error deleting item: ' . $conn->error;
            }
        } else {
            $successMessage = 'Error scrapping item: ' . $conn->error;
        }
    } else {
        $successMessage = "No details found for ID $delete_id.";
    }
}

// Get details by ID
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["get_id"])) {
    $get_id = $_POST["get_id"];

    $details_sql = "SELECT id, date, item_name, department, room, desktop_ID FROM issue WHERE id = $get_id";
    $result_details = $conn->query($details_sql);

    if ($result_details->num_rows > 0) {
        $details_row = $result_details->fetch_assoc();
        $detailsMessage = "Details for ID $get_id: Date - ".$details_row["date"].", Item Name - ".$details_row["item_name"].", Department - ".$details_row["department"].", Room - ".$details_row["room"].", Desktop ID - ".$details_row["desktop_ID"];
    } else {
        $detailsMessage = "No details found for ID $get_id.";
    }
}

// Fetch the last 7 entries from the "scrap" table
$scrap_data_sql = "SELECT item_name, department, room, desktop_ID, identification_no, verifyed_by, Specification, timedate
                   FROM scrap
                   ORDER BY id DESC
                   LIMIT 7";

$scrap_result = $conn->query($scrap_data_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Scrap Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="sidebar">
        <header>
            <img src="assets/scrapimg.png" alt="Company Logo">
            <a href="start.php"> <h1>Menu</h1> </a>
        </header>
        <nav>


<div class="nav-option"><a href="add.php" class="nav-button" >Add Item</a></div>
<div class="nav-option"><a href="issue.php" class="nav-button">Issue Item</a></div>
<div class="nav-option"><a href="desktop.php" class="nav-button">Issue Desktop</a></div>
<div class="nav-option"><a href="stock.php" class="nav-button">Stock</a></div>
<div class="nav-option">
    <a href="search.php" class="nav-button">Search</a>
</div>
<div class="nav-option"><a href="scrap.php" class="nav-button"style="background-color: #3498db;">Scrap</a></div>

<div class="nav-option"><a href="report.html" class="nav-button">Report</a></div>
<div class="nav-option"><a href="chat.php" class="nav-button">Chat</a></div>
<div class="nav-option"><a href="login/register.php" class="nav-button">Register</a></div>

</nav>
    </div>
    <div id="content">
        
        <header style="background-color:  #979292;">
            <img src="assets/imgcl.png" alt="Company Logo">
        </header>
        <section id="scrap-form" >
    <h2>Scrap Item</h2>
    <form method="post">
        <label for="input_id">Enter ID:</label>
        <input type="text" id="input_id" name="get_id" required>
        <button type="submit">Get Details</button>
    </form>

    <?php
    if (isset($detailsMessage)) {
        echo "<p>$detailsMessage</p>";
        echo "<form method='post' onsubmit='return confirm(\"Are you sure you want to delete this item?\")'>
                <input type='hidden' name='delete_id' value='$get_id'>
                <label for='verified_by'>Verified By:</label>
                <input type='text' id='verified_by' name='verified_by' required>
                <label for='specification'>Specification:</label>
                <input type='text' id='specification' name='specification' required>
                <button type='submit'>Scrap Item</button>
              </form>";
    }

    if (isset($successMessage)) {
        echo "<p>$successMessage</p>";
    }

    // ... (Your existing code)

    $conn->close(); // Close the connection after all operations are done
    ?>

<h2>Scraped Details</h2>

<?php
if ($scrap_result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Item Name</th>
                <th>Department</th>
                <th>Room</th>
                <th>Desktop ID</th>
                <th>Identification No</th>
                <th>Verified By</th>
                <th>Specification</th>
                <th>Date</th>
            </tr>";

    // Output data of each row
    while ($scrap_row = $scrap_result->fetch_assoc()) {
        echo "<tr>
                <td>" . $scrap_row["item_name"] . "</td>
                <td>" . $scrap_row["department"] . "</td>
                <td>" . $scrap_row["room"] . "</td>
                <td>" . $scrap_row["desktop_ID"] . "</td>
                <td>" . $scrap_row["identification_no"] . "</td>
                <td>" . $scrap_row["verifyed_by"] . "</td>
                <td>" . $scrap_row["Specification"] . "</td>
                <td>" . $scrap_row["timedate"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No scrap details found.";
}
?>
</body>
</html>
