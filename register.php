<?php
// Start a session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are provided
    if (isset($_POST["fullName"]) && isset($_POST["Username"]) && isset($_POST["Password"]) && isset($_POST["confirmPassword"])) {
        // Retrieve data from the submitted form
        $fullName = $_POST["fullName"];
        $username = $_POST["Username"];
        $password = $_POST["Password"];
        $confirmPassword = $_POST["confirmPassword"];

        if ($password != $confirmPassword) {
            // Redirect to an error page if passwords do not match
            header("Location: register.php?error=password_mismatch");
            exit;
        }

        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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

        // Prepare and execute an insert statement
        $stmt = $conn->prepare("INSERT INTO form (`First Name`, Username, Password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullName, $username, $hashedPassword);

        if ($stmt->execute()) {
            // Redirect to a success page after successful registration
            header("Location: login.php?registration=success");
            exit;
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    }
} else {
    // If the form is not submitted, do nothing
}
?>
