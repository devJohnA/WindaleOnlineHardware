<?php
session_start();
include 'admin/dbcon/conn.php';

$sessionId = isset($_GET['sessionId']) ? $_GET['sessionId'] : '';

if (!empty($sessionId)) {
    // Check the admin status
    $statusQuery = "SELECT status, default_message FROM admin_status ORDER BY timestamp DESC LIMIT 1";
    $statusResult = $conn->query($statusQuery);
    $adminStatus = 'online';
    $defaultMessage = '';
    
    if ($statusResult->num_rows > 0) {
        $statusRow = $statusResult->fetch_assoc();
        $adminStatus = $statusRow['status'];
        $defaultMessage = $statusRow['default_message'];
    }

    $sql = "SELECT m.*, 'user' as type, ar.reply_message, ar.timestamp as reply_timestamp
            FROM messages m
            LEFT JOIN admin_replies ar ON m.id = ar.message_id
            WHERE m.session_id = ?
            ORDER BY m.timestamp ASC, ar.timestamp ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    $defaultMessageSent = false;

    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'id' => $row['id'],
            'sender' => $row['sender'],
            'message' => $row['message'],
            'timestamp' => $row['timestamp'],
            'type' => $row['type'],
            'image_path' => $row['image_path']
        ];

        // Add default message after the first user message
        if (!$defaultMessageSent) {
            $messages[] = [
                'id' => 'admin_default',
                'sender' => 'Admin',
                'message' => $defaultMessage,
                'timestamp' => date('Y-m-d H:i:s', strtotime($row['timestamp'] . ' +1 second')),
                'type' => 'admin',
                'image_path' => null
            ];
            $defaultMessageSent = true;
        }

        if (!empty($row['reply_message'])) {
            $messages[] = [
                'id' => 'admin_' . $row['id'],
                'sender' => 'Admin',
                'message' => $row['reply_message'],
                'timestamp' => $row['reply_timestamp'],
                'type' => 'admin',
                'image_path' => null
            ];
        }
    }

    echo json_encode($messages);
} else {
    echo json_encode([]);
}

$conn->close();
?>