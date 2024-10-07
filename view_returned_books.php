<?php
include 'dbcon.php'; // Database connection

// Fetch returned books from borrow_requests table
$query = "SELECT b.Accno, b.Booktitle, br.request_date as borrow_date, br.return_date 
          FROM borrow_requests br 
          JOIN book b ON br.book_id = b.id 
          WHERE br.status = 'returned'"; // Only fetch returned books

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Returned Books</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body >
    <div class="container mt-5">
        <h2>Returned Books</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Acc No</th>
                    <th>Title</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['Accno']; ?></td>
                        <td><?php echo $row['Booktitle']; ?></td>
                        <td><?php echo $row['borrow_date']; ?></td>
                        <td><?php echo $row['return_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
