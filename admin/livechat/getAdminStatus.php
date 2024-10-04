<?php
include '../dbcon/conn.php';

// Fetch the latest status and default message from the database
$query = "SELECT status, default_message FROM admin_status ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode([
        'status' => $row['status'],
        'defaultMessage' => $row['default_message']
    ]);
} else {
    echo json_encode([
        'status' => 'offline',
        'defaultMessage' => 'The admin is currently offline. Please leave a message.'
    ]);
}

mysqli_close($conn);
?>