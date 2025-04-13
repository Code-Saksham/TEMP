<?php
// Start the session
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user information
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Handle logout
if (isset($_GET['logout'])) {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header('Location: login.php');
    exit;
}

// Get all books from database
$books_query = "SELECT * FROM books ORDER BY title";
$books_result = $conn->query($books_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bookstore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation Bar -->
    <nav class="bg-blue-600 p-4 text-white flex justify-between shadow-md">
        <h1 class="text-2xl font-bold">📚 Bookstore Dashboard</h1>
        <div class="flex items-center space-x-4">
            <span>Welcome, <?= htmlspecialchars($user_name) ?></span>
            <a href="dashboard.php?logout=1" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-2xl font-bold mb-4">Your Dashboard</h2>
            <p>Welcome to your personal bookstore dashboard. Here you can manage your book collection and preferences.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Book Collection Section -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold mb-4">Book Collection</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 border">Title</th>
                                <th class="p-2 border">Author</th>
                                <th class="p-2 border">Genre</th>
                                <th class="p-2 border">Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($books_result->num_rows > 0): ?>
                                <?php while($book = $books_result->fetch_assoc()): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-2 border"><?= htmlspecialchars($book['title']) ?></td>
                                        <td class="p-2 border"><?= htmlspecialchars($book['author']) ?></td>
                                        <td class="p-2 border"><?= htmlspecialchars($book['genre']) ?></td>
                                        <td class="p-2 border"><?= htmlspecialchars($book['published_year']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="p-2 border text-center">No books available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- User Profile Section -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold mb-4">User Profile</h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($user_name) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['user_email']) ?></p>
                <p><strong>Member Since:</strong> <?= date('F j, Y') ?></p>
                
                <div class="mt-4">
                    <h4 class="font-bold">Account Options</h4>
                    <ul class="list-disc pl-5 mt-2">
                        <li><a href="#" class="text-blue-500 hover:underline">Edit Profile</a></li>
                        <li><a href="#" class="text-blue-500 hover:underline">Change Password</a></li>
                        <li><a href="index.php" class="text-blue-500 hover:underline">Go to Homepage</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
