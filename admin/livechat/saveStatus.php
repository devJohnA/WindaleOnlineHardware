<?php
include '../dbcon/conn.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $defaultMessage = isset($_POST['defaultMessage']) ? $_POST['defaultMessage'] : '';

    if (!empty($status) && !empty($defaultMessage)) {
        $sql = "INSERT INTO admin_status (status, default_message) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $status, $defaultMessage);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Status updated successfully!";
        } else {
            $response['message'] = "Error: " . $stmt->error;
        }
    } else {
        $response['message'] = "Status and default message are required.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

$conn->close();
echo json_encode($response);
exit;
?>