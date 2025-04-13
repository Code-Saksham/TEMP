<?php
// Start the session
session_start();
require_once 'config.php';

$error = '';

// Login validation logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        // Check user credentials
        $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Redirect to dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bookstore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-green-50 to-green-100 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-1/2 lg:w-1/3">
        <h2 class="text-3xl font-bold mb-4 text-green-600 flex items-center">
            <span class="material-icons mr-2">login</span> Login to Your Account
        </h2>

        <?php if (!empty($error)) : ?>
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" placeholder="Enter your email" class="w-full p-3 border rounded shadow-sm focus:ring-2 focus:ring-green-400" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" placeholder="Enter your password" class="w-full p-3 border rounded shadow-sm focus:ring-2 focus:ring-green-400" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white p-3 rounded shadow hover:bg-green-700 transition-colors">
                Login
            </button>
        </form>
        <p class="text-center mt-4 text-gray-600">
            Don't have an account? 
            <a href="signup.php" class="text-green-500 hover:underline">Signup here</a>
        </p>
    </div>

    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</body>
</html>
