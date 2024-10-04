<?php
session_start();
include '../dbcon/conn.php';

// Set the default timezone to Philippines
date_default_timezone_set('Asia/Manila');

// Function to fetch the latest message for each user
function getLatestMessages($conn) {
    $sql = "SELECT m1.*
            FROM messages m1
            INNER JOIN (
                SELECT sender, MAX(timestamp) as max_timestamp
                FROM messages
                GROUP BY sender
            ) m2 ON m1.sender = m2.sender AND m1.timestamp = m2.max_timestamp
            ORDER BY m1.timestamp DESC";

    $result = $conn->query($sql);
    $users = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = [
                'id' => $row['id'],
                'name' => $row['sender'],
                'message' => $row['message'],
                'timestamp' => (new DateTime($row['timestamp']))->format('Y-m-d H:i:s')
            ];
        }
    }

    return $users;
}

// Check admin status
$statusQuery = "SELECT status, default_message FROM admin_status ORDER BY timestamp DESC LIMIT 1";
$statusResult = $conn->query($statusQuery);

$adminStatus = 'online';
$defaultMessage = '';

if ($statusResult->num_rows > 0) {
    $statusRow = $statusResult->fetch_assoc();
    $adminStatus = $statusRow['status'];
    $defaultMessage = $statusRow['default_message'];
}

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $sql = "SELECT m.*, m.sender as username, ar.reply_message, ar.timestamp as reply_timestamp, m.image_path
            FROM messages m
            LEFT JOIN admin_replies ar ON m.id = ar.message_id 
            WHERE m.sender = (SELECT sender FROM messages WHERE id = ?)
            ORDER BY COALESCE(ar.timestamp, m.timestamp) ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $conversations = [];
    $lastMessageTime = null;

    while ($row = $result->fetch_assoc()) {
        $conversations[] = [
            'sender' => 'user',
            'username' => $row['username'],
            'message' => $row['message'],
            'timestamp' => (new DateTime($row['timestamp']))->format('Y-m-d H:i:s'),
            'image_path' => $row['image_path']
        ];

        if (!empty($row['reply_message'])) {
            $conversations[] = [
                'sender' => 'admin',
                'username' => 'Admin',
                'message' => $row['reply_message'],
                'timestamp' => (new DateTime($row['reply_timestamp']))->format('Y-m-d H:i:s'),
                'image_path' => null
            ];
        }

        $lastMessageTime = $row['timestamp'];
    }

    // Check if admin is offline and no reply has been sent
    if ($adminStatus == 'offline' && $lastMessageTime) {
        $lastReplyTime = end($conversations)['timestamp'];
        if ($lastMessageTime > $lastReplyTime) {
            $conversations[] = [
                'sender' => 'admin',
                'username' => 'Admin',
                'message' => $defaultMessage,
                'timestamp' => (new DateTime())->format('Y-m-d H:i:s'),
                'image_path' => null
            ];
        }
    }

    echo json_encode(['conversations' => $conversations]);
} else {
    $users = getLatestMessages($conn);
    echo json_encode(['users' => $users]);
}

$conn->close();
?>