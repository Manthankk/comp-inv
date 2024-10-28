<?php


session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $username = $_SESSION["username"];
    $branch = isset($_SESSION["branch"]) ? $_SESSION["branch"] : "N/A";
} else {
    // Redirect to login if not logged in
    header("Location: ./login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body >
    <div id="sidebar">
    <header>
            <img src="../assets/development.png" alt="Company Logo">
            <a href="../login/index.php">
    <button style="background-color: #4285F4; /* Red */
                    border: none;
                    color: white;
                    padding: 10px 20px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 4px 2px;
                    cursor: pointer;
                    border-radius: 4px;">
        Logout
    </button>
</a>
        </header>
        <nav>
       <!--      <div class="nav-option"><a href="add.php" class="nav-button">Add Item</a></div>
            <div class="nav-option"><a href="issue.php" class="nav-button">Issue Item</a></div>
            <div class="nav-option"><a href="desktop.php" class="nav-button">Issue Desktop</a></div>
            <div class="nav-option"><a href="stock.php" class="nav-button">Stock</a></div> -->
            <div class="nav-option">
                <a href="user_search.php" class="nav-button">Search</a>
            </div>
          <!--   <div class="nav-option"><a href="scrap.php" class="nav-button">Scrap</a></div>

            <div class="nav-option"><a href="report.html" class="nav-button">Report</a></div> -->
            <div class="nav-option"><a href="#" class="nav-button">Chat</a></div>
            <!-- <div class="nav-option"><a href="login/register.php" class="nav-button">Register</a></div> -->
        </nav>
    </div>
    <div id="content">
        
        <header style="background-color:  #979292;">
            <img src="../assets/imgcl.png" alt="Company Logo">
        </header>
            



<!--             <body style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">
                <div style="text-align: center;">
                    <h1>Welcome to Inventory Management System</h1>
                  <img src="11065.jpg" alt="Image" style="max-width: 65%; max-height: 65%;">
                </div>
              </body> -->
              <body style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">
                <div style="text-align: center;">
                    <h1>Welcome! <?php echo htmlspecialchars($username); ?></h1>
                    
                   <h2><p>Branch: <?php echo htmlspecialchars($branch); ?></p></h2> 
                    <img src="./11065.jpg" alt="Image" style="max-width: 65%; max-height: 65%;">
                </div>
            </body>
    
        <footer>
            <p>MCT's Rajiv Gandhi Institute of Technology,Versova.</p>
        </footer>
    </div>
</body>
</html>
