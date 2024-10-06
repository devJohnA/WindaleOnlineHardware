<?php
include '../dbcon/conn.php';

$response = ['success' => false, 'message' => ''];

if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    $sql = "UPDATE messages SET `read` = 1 WHERE sender = (SELECT sender FROM messages WHERE id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Read status updated successfully';
    } else {
        $response['message'] = 'Error updating read status: ' . $conn->error;
    }
    
    $stmt->close();
} else {
    $response['message'] = 'No user ID provided';
}

$conn->close();
echo json_encode($response);
?>