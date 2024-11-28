<?php
require_once '../../admin/dbcon/conn.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $conn->real_escape_string($_POST['productName']);
    $productCategory = $conn->real_escape_string($_POST['productCategory']);
    $productPrice = $conn->real_escape_string($_POST['productPrice']);
    $productStock = $conn->real_escape_string($_POST['productStock']);
    $checkStock = $conn->real_escape_string($_POST['checkStock']);
    $productDate = $conn->real_escape_string($_POST['productDate']);

    // Check if product already exists
    $checkSql = "SELECT COUNT(*) as count FROM stocks WHERE productName = '$productName'";
    $checkResult = $conn->query($checkSql);
    $checkRow = $checkResult->fetch_assoc();

    if ($checkRow['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'This product name already exists!']);
        exit();
    }

    $targetDir = "upload/";
    $fileName = basename($_FILES["images"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array(strtolower($fileType), $allowTypes)) {
        if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFilePath)) {
            $conn->begin_transaction();

            try {
                $sql = "INSERT INTO stocks (productName, productCategory, productPrice, productStock, checkStock, productDate, images)
                        VALUES ('$productName', '$productCategory', '$productPrice', $productStock, $checkStock, '$productDate', '$fileName')";
                $conn->query($sql);

                $conn->commit();

                echo json_encode(['success' => true, 'message' => 'Product added successfully!']);
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Sorry, there was an error uploading your file.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Sorry, only JPG, JPEG, PNG, GIF files are allowed.']);
        exit();
    }
}

$conn->close();
?>