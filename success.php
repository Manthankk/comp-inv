<?php
// ... (database connection and fetch top 5 items code)

// Check if the session variable indicates successful item addition
if (isset($_SESSION["item_added"]) && $_SESSION["item_added"] === true) {
    // Display success message
    echo '<div id="popup" class="popup-message">';
    echo '<div>Item added successfully</div>';
    echo '</div>';
    
    // Display the top 5 items
    // ... (the code you used to display the table with top 5 items)
    
    // Reset the session variable to avoid showing the success message on further refreshes
    $_SESSION["item_added"] = false;
} else {
    // Redirect the user back to the add item page if there's no success indication
    header("Location: add.html");
    exit; // Important: Terminate the script to ensure the redirect happens
}
// ... (close the database connection)
?>
