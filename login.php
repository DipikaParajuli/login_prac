<?php
ob_end_flush(); // This line is not necessary here
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Database connection details
        $servername = "localhost"; // Change if your database server is on a different host
        $db_username = "root"; // Change to your database username
        $db_password = ""; // Change to your database password
        $db_name = "register"; // Change to your database name

        // Establish a database connection
        $conn = new mysqli($servername, $db_username, $db_password, $db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute a SELECT statement to retrieve the hashed password
        $stmt = $conn->prepare("SELECT Password FROM form WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // Bind the result
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            // Verify the entered password against the hashed password from the database
            if (password_verify($password, $hashedPassword)) {
                // Set a session variable for successful login
                $_SESSION["username"] = $username;
                header("Location: dashboard.php"); // Redirect immediately
                exit;
            } else {
                // Password authentication failed
                header("Location: login.php?error=authentication_failed");
                exit;
            }
        } else {
            // Username not found in the database
            header("Location: login.php?error=username_not_found");
            exit;
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    }
}
?>
