<?php
ob_end_flush(); // Disable output buffering
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if both username and password are provided
    // if (isset($_POST["username"]) && isset($_POST["password"])) {
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {

        // Retrieve username and password from the form
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Connect to the database
        $servername = "localhost"; // Change if your database server is on a different host
        $db_username = "root"; // Change to your database username
        $db_password = ""; // Change to your database password
        $db_name = "register"; // Change to your database name

        // Create a connection
        $conn = new mysqli($servername, $db_username, $db_password, $db_name);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute a SELECT query to fetch the user's password hash from the database
        $stmt = $conn->prepare("SELECT Password FROM form WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Check if a row with the given username exists
        if ($stmt->num_rows == 1) {
            // Bind the result
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();
            // Verify the password hash
            // echo "password_verify($password, $hashedPassword) passverify";
            if (password_verify($password, $hashedPassword)) {
                // Authentication successful
                // Store username in session for later use if needed
                $_SESSION["username"] = $username;
                echo "Login Successful";
                // Redirect the user to a dashboard or another page
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Hashed Password from DB: $hashedPassword and $password<br>";
                // echo "Hashed Password Entered: " . password_hash($password, PASSWORD_DEFAULT) . "<br>";
                header("Location: login.php?error=authentication_failed");
                exit;
            }
        } else {
            header("Location: login.php?error=username_not_found");
            exit;
        }
        $stmt->close();
        $conn->close();
    }
}
exit;
?>
