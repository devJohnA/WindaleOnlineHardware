<?php
session_start();

include 'admin/dbcon/conn.php';

$response = ['success' => false, 'message' => '', 'sessionId' => null];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $sessionId = isset($_POST['sessionId']) ? $_POST['sessionId'] : null;

    if (empty($message)) {
        $response['message'] = "Message is required.";
        echo json_encode($response);
        exit;
    }

    if (!$sessionId) {
        $sessionId = uniqid();
    }

// If no name is provided, use the stored name or 'Anonymous'
if (empty($name)) {
    $name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Anonymous';
} else {
    $_SESSION['user_name'] = $name;
}

$_SESSION['sessionId'] = $sessionId;
$response['sessionId'] = $sessionId;
    }
    if (!empty($name) && !empty($message)) {
        $image_path = null;

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $_FILES["image"]["name"];
            $filetype = $_FILES["image"]["type"];
            $filesize = $_FILES["image"]["size"];

            // Verify file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

            // Verify file size - 5MB maximum
            $maxsize = 5 * 1024 * 1024;
            if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

            // Verify MYME type of the file
            if (in_array($filetype, $allowed)) {
                // Check whether file exists before uploading it
                $target_dir = "uploads/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $target_file = $target_dir . uniqid() . "_" . basename($filename);

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    exit;
                }
            } else {
                echo "Error: There was a problem uploading your file. Please try again.";
                exit;
            }
        }

        $sql = "INSERT INTO messages (sender, message, image_path, session_id, `read`) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $name, $message, $image_path, $sessionId);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Message sent successfully!";
        } else {
            $response['message'] = "Error: " . $stmt->error;
        }
}

$conn->close();
echo json_encode($response);
?>