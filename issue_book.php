<?php
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];

    // Set the book as issued and set a return date (14 days from now)
    $return_date = date('Y-m-d', strtotime('+14 days'));

    $sql = "UPDATE borrow_requests SET status = 'approved', return_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $return_date, $request_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->error;
    }
}
?>
