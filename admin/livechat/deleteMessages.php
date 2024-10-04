<?php
session_start();
include '../dbcon/conn.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the POST data
    $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;

    if ($userId) {
        // Prepare and execute the delete query
        $stmt = $conn->prepare("DELETE FROM messages WHERE sender = (SELECT sender FROM messages WHERE id = ?)");
        $stmt->bind_param("i", $userId);
        
        if ($stmt->execute()) {
            // Delete associated admin replies
            $stmt2 = $conn->prepare("DELETE FROM admin_replies WHERE message_id IN (SELECT id FROM messages WHERE sender = (SELECT sender FROM messages WHERE id = ?))");
            $stmt2->bind_param("i", $userId);
            $stmt2->execute();

            echo json_encode(['success' => true, 'message' => 'Messages deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete messages']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>