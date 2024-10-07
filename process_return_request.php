<?php
// Include database connection file
include 'dbcon.php';

$request_id = $_POST['request_id'];
$book_id = $_POST['book_id'];

// Update the borrow request to mark the book as returned and set the return date
$return_date = date('Y-m-d'); // Current date as the return date
$sql = "UPDATE borrow_requests SET status = 'returned', return_date = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $return_date, $request_id);
$stmt->execute();

// If the update is successful, increase the available copies of the book
if ($stmt->affected_rows > 0) {
    // Increase the available copies by 1
    $update_copies_sql = "UPDATE book SET Copies = Copies + 1 WHERE id = ?";
    $update_stmt = $conn->prepare($update_copies_sql);
    $update_stmt->bind_param("i", $book_id);
    $update_stmt->execute();
    
    // Redirect back to the borrow history page with a success message
    header("Location: borrow_history.php?message=Book returned successfully.");
} else {
    // Redirect back with an error message if the return fails
    header("Location: borrow_history.php?message=Failed to return the book.");
}

$stmt->close();
$conn->close();
?>
