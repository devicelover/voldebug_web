<?php
session_start(); // Start the session
include 'config.php';
$conn = $con; // Alias for compatibility

if (isset($_POST['submit'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['u_email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Check if user already exists
    $sql = "SELECT * FROM `users` WHERE u_email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "User already exists.";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO `users` (first_name, last_name, u_email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

    if ($stmt->execute()) {
        // Store user information in session variables
        
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;

        echo "Registration successful.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}