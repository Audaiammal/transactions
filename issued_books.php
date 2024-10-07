<?php
include 'dbcon.php';

// Fetch approved and issued books
$sql = "SELECT br.id, br.book_id, br.member_id, m.firstname, m.lastname, m.`roll number`, b.Booktitle, br.request_date, br.return_date
        FROM borrow_requests br 
        JOIN member m ON br.member_id = m.member_id 
        JOIN book b ON br.book_id = b.id 
        WHERE br.status = 'approved'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issued Books</title>
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center" style="color: navy;">Issued Books</h3>
        <table class="table table-bordered table-responsive-md">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Member Name</th>
                    <th>Roll Number</th>
                    <th>Request Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['Booktitle']; ?></td>
                        <td><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
                        <td><?php echo $row['roll number']; ?></td>
                        <td><?php echo $row['request_date']; ?></td>
                        <td><?php echo $row['return_date'] ? $row['return_date'] : 'Not returned'; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
