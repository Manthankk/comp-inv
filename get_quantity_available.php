<?php
include 'config.php';

// Check if the item name is provided in the request
if(isset($_POST['item_name'])) {
    // Sanitize the input to prevent SQL injection
    $item_name = $conn->real_escape_string($_POST['item_name']);
    
    // Query to fetch the quantity available for the specified item
    $query = "SELECT quantity_available FROM inventroy WHERE item_name = '$item_name'";
    
    // Execute the query
    $result = $conn->query($query);
    
    // Check if the query was successful
    if($result) {
        // Fetch the quantity available from the result
        $row = $result->fetch_assoc();
        $quantity_available = $row['quantity_available'];
        
        // Return the quantity available as the response
        echo $quantity_available;
    } else {
        // Return an error message if the query fails
        echo "Error: Unable to fetch quantity available";
    }
} else {
    // Return an error message if the item name is not provided in the request
    echo "Error: Item name not specified";
}

// Close the database connection
$conn->close();
?>
