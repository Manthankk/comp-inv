<?php
// Connect to your database (replace these with your actual database credentials)
include 'config.php';

$query = "SELECT name FROM registration WHERE role = 'user' OR role = 'admin'";
$result = mysqli_query($conn, $query);

$users = array();
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

$conn->close();

// Return the user data as JSON
echo json_encode($users);
?>
