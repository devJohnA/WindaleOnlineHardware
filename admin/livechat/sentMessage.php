<?php
session_start();

include '../dbcon/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $replyMessage = isset($_POST['reply_message']) ? trim($_POST['reply_message']) : '';
    $userId = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';

    if (!empty($replyMessage) && !empty($userId)) {
        // Find the message ID and sender name corresponding to the user ID
        $sql = "SELECT id, sender FROM messages WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param('i', $userId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $messageId = $row['id'];
                $senderName = $row['sender'];

                // Insert the reply into the admin_replies table
                $sql = "INSERT INTO admin_replies (message_id, reply_message) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt === false) {
                    die("Prepare failed: " . $conn->error);
                }

                $stmt->bind_param('is', $messageId, $replyMessage);

                if ($stmt->execute()) {
                    echo "Reply sent successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "No message found for this user.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Reply message and user ID are required.";
    }
}

$conn->close();
?>