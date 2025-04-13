<?php
// Start the session
session_start();
require_once 'config.php';

$error = '';
$success = '';

// Register new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Email already exists. Please use a different email or login.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $full_name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Registration successful! You can now login.";
                // Redirect to login after 2 seconds
                header("Refresh: 2; URL=login.php");
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Bookstore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-50 to-blue-100 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-1/2 lg:w-1/3">
        <h2 class="text-3xl font-bold mb-4 text-blue-600 flex items-center">
            <span class="material-icons mr-2">person_add</span> Create an Account
        </h2>

        <?php if (!empty($error)) : ?>
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)) : ?>
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Full Name</label>
                <input type="text" name="full_name" placeholder="Enter your full name" class="w-full p-3 border rounded shadow-sm focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" placeholder="Enter your email" class="w-full p-3 border rounded shadow-sm focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" placeholder="Create a password" class="w-full p-3 border rounded shadow-sm focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm your password" class="w-full p-3 border rounded shadow-sm focus:ring-2 focus:ring-blue-400" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded shadow hover:bg-blue-700 transition-colors">
                Sign Up
            </button>
        </form>
        <p class="text-center mt-4 text-gray-600">
            Already have an account? 
            <a href="login.php" class="text-blue-500 hover:underline">Login here</a>
        </p>
    </div>

    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</body>
</html>
