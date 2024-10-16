<?php
include '../dbcon/conn.php';

$query = "SELECT
    COUNT(DISTINCT sender) as unread_count,
    MAX(latest_timestamp) as latest_timestamp
FROM (
    SELECT
        sender,
        MAX(timestamp) as latest_timestamp
    FROM messages
    WHERE timestamp > (
        SELECT COALESCE(MAX(timestamp), '1970-01-01 00:00:00')
        FROM messages AS m2
        WHERE m2.sender = messages.sender AND m2.`read` = 1
    )
    GROUP BY sender
) as unread_senders";

$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $unread_count = intval($row['unread_count']);
    $latest_timestamp = $row['latest_timestamp'];
} else {
    // Handle query error
    $unread_count = 0;
    $latest_timestamp = null;
    error_log("Error in get_unread_count.php: " . $conn->error);
}

// Ensure proper JSON response
header('Content-Type: application/json');
echo json_encode([
    'unread_count' => $unread_count,
    'latest_timestamp' => $latest_timestamp
]);

$conn->close();
?>