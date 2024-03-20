


<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are provided
    if (isset($_POST["fullName"]) && isset($_POST["Username"]) && isset($_POST["Password"]) && isset($_POST["confirmPassword"])) {
        // Retrieve data from the form
        $fullName = $_POST["fullName"];
        $username = $_POST["Username"];
        $password = $_POST["Password"];
        $confirmPassword = $_POST["confirmPassword"];

        // Perform validation
        if ($password != $confirmPassword) {
            // Password and confirm password do not match
            header("Location: register.php?error=password_mismatch");
            exit;
        }

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

        // Hash the password for security
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashedPassword = $password;
        // Prepare and bind the insert statement
        $stmt = $conn->prepare("INSERT INTO form (`First Name`, Username, Password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullName, $username, $hashedPassword);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            // Redirect the user to the login page or any other page
            header("Location: login.php?registration=success");
            exit;
        } else {
            // Registration failed
            // Redirect the user back to the registration page with an error message
            header("Location: register.php?error=registration_failed");
            exit;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
} else {
    // If the form is not submitted, do nothing
}
?>
