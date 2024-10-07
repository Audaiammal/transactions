<?php
// reject_request.php (Rejecting a borrow request)
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];

    // Reject the request
    $sql = "UPDATE borrow_requests SET status = 'rejected' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);

    if ($stmt->execute()) {
        echo 'success'; // Send 'success' response
    } else {
        echo 'Error: ' . $stmt->error;
    }
}
?>
