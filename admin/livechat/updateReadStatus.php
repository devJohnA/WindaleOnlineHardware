<?php
// Include your database connection file here
include '../dbcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['id'];
    
    // Prepare SQL statement to update read status
    $sql = "UPDATE messages SET `read` = 1 WHERE id = ? AND `read` = 0";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Read status updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating read status"]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>