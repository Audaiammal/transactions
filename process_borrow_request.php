<?php
// Include database connection file
include 'dbcon.php';

session_start(); // Start the session

// Check if the member is logged in and the request is coming from the form
if (isset($_POST['member_id']) && isset($_POST['book_id'])) {
    $member_id = $_POST['member_id'];
    $book_id = $_POST['book_id'];

    // Check if the member has already borrowed a book and hasn't returned it yet
    $checkBorrow = $conn->prepare("SELECT * FROM borrow_requests WHERE member_id = ? AND book_id = ? AND status = 'approved'");
    $checkBorrow->bind_param("ii", $member_id, $book_id);
    $checkBorrow->execute();
    $result = $checkBorrow->get_result();

    if ($result->num_rows > 0) {
        // If the member already has this book borrowed and not returned, show an error message
        echo "<script>alert('You have already borrowed this book and not yet returned it.'); window.location.href = 'browse_books.php';</script>";
    } else {
        // Get the current date (without time)
        $request_date = date('Y-m-d'); // Capture current date in Y-m-d format

        // Insert the borrow request with the current date
        $insertBorrow = $conn->prepare("INSERT INTO borrow_requests (member_id, book_id, request_date, status) VALUES (?, ?, ?, 'pending')");
        $insertBorrow->bind_param("iis", $member_id, $book_id, $request_date);

        if ($insertBorrow->execute()) {
            // Success - show a success message
            echo "<script>alert('Your borrow request has been sent successfully.'); window.location.href = 'browse_books.php';</script>";
        } else {
            // Error handling
            echo "<script>alert('Failed to send borrow request. Please try again later.'); window.location.href = 'browse_books.php';</script>";
        }

        $insertBorrow->close(); // Close the statement
    }

    $checkBorrow->close(); // Close the statement
} else {
    // If the request does not have the required fields, show an error message
    echo "<script>alert('Invalid request.'); window.location.href = 'browse_books.php';</script>";
}

$conn->close(); // Close the database connection
?>
