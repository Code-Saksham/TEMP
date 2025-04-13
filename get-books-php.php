<?php
// get_books.php
require_once 'config.php';

// Get search parameter if exists
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';

// Prepare query
$query = "SELECT * FROM books WHERE 1=1";

// Add search filter if provided
if (!empty($search)) {
    $search = "%$search%";
    $query .= " AND (title LIKE ? OR author LIKE ?)";
}

// Add genre filter if provided
if (!empty($genre)) {
    $query .= " AND genre = ?";
}

$query .= " ORDER BY title";

// Prepare and execute statement
$stmt = $conn->prepare($query);

// Bind parameters
if (!empty($search) && !empty($genre)) {
    $stmt->bind_param("sss", $search, $search, $genre);
} elseif (!empty($search)) {
    $stmt->bind_param("ss", $search, $search);
} elseif (!empty($genre)) {
    $stmt->bind_param("s", $genre);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch all books
$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($books);
?>
