<?php
include 'dbcon.php';

// Fetch pending borrow requests
$sql = "SELECT br.id, br.book_id, br.member_id, m.firstname, m.lastname, m.`roll number`, b.Booktitle, br.request_date 
        FROM borrow_requests br 
        JOIN member m ON br.member_id = m.member_id 
        JOIN book b ON br.book_id = b.id 
        WHERE br.status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Requests</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            background-color: #e0f7fa; 
            border-color: navy;
        }
        thead {
            background-color: navy;
            color: white;
        }
        .btn-approve, .btn-reject {
            background-color: rgb(20, 12, 109);
            color: white;
        }
        .btn-approve:hover, .btn-reject:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center" style="color: navy;">Borrow Requests</h3>
        <table class="table table-bordered table-responsive-md">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Member Name</th>
                    <th>Roll Number</th>
                    <th>Request Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="borrowRequestsTable">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr id="request-<?php echo $row['id']; ?>">
                        <td><?php echo $row['Booktitle']; ?></td>
                        <td><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
                        <td><?php echo $row['roll number']; ?></td>
                        <td><?php echo $row['request_date']; ?></td>
                        <td>
                            <button class="btn btn-approve btn-sm" onclick="approveRequest(<?php echo $row['id']; ?>, <?php echo $row['book_id']; ?>, <?php echo $row['member_id']; ?>)">Approve</button>
                            <button class="btn btn-reject btn-sm" onclick="rejectRequest(<?php echo $row['id']; ?>)">Reject</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        function approveRequest(requestId, bookId, memberId) {
            fetch('issue_book.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `request_id=${requestId}&book_id=${bookId}&member_id=${memberId}`
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') {
                    alert('Book issued successfully!');
                    document.getElementById(`request-${requestId}`).remove(); // Remove the row after issuing
                } else {
                    alert('Error issuing the book: ' + data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error);
            });
        }

        function rejectRequest(requestId) {
            fetch('reject_request.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `request_id=${requestId}`
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') {
                    alert('Request rejected successfully!');
                    document.getElementById(`request-${requestId}`).remove(); // Remove the row after rejecting
                } else {
                    alert('Error rejecting request: ' + data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error);
            });
        }
    </script>
</body>
</html>
