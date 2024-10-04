<?php

include 'admin/dbcon/conn.php';

$name = isset($_GET['name']) ? $conn->real_escape_string($_GET['name']) : '';

$sql = "SELECT session_id FROM messages WHERE sender = ? ORDER BY timestamp DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['exists' => true, 'sessionId' => $row['session_id']]);
} else {
    echo json_encode(['exists' => false]);
}

$conn->close();
?>