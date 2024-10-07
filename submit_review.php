<?php
define('DB_SERVER', 'localhost');
define('DB_USER', 'u510162695_dried');
define('DB_PASS', '1Dried_password');
define('DB_NAME', 'u510162695_dried');

// Create connection
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        error_log("CSRF token validation failed");
        echo json_encode(['success' => false, 'message' => 'Security check failed']);
        exit();
    }

    // Unset the used token
    unset($_SESSION['csrf_token']);

    // Validate and sanitize inputs
    $proid = filter_var($_POST['proid'], FILTER_VALIDATE_INT);
    $name = validate_input($_POST['name']);
    $rating = filter_var($_POST['rating'], FILTER_VALIDATE_INT,
                         array("options" => array("min_range" => 1, "max_range" => 5)));
    $reviewtext = validate_input($_POST['reviewtext']);

    // Validate inputs
    if ($proid === false || empty($name) || $rating === false || empty($reviewtext)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit();
    }

    // Rate limiting (1 review per minute)
    if (isset($_SESSION['last_review_time']) && (time() - $_SESSION['last_review_time'] < 60)) {
        echo json_encode(['success' => false, 'message' => 'Please wait before submitting another review']);
        exit();
    }

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO tblcustomerreview (PROID, CUSTOMERNAME, RATING, REVIEWTEXT) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Error preparing statement: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Error preparing database query']);
        exit();
    }

    $stmt->bind_param("isis", $proid, $name, $rating, $reviewtext);

    if ($stmt->execute()) {
        $_SESSION['last_review_time'] = time();
        echo json_encode(['success' => true, 'message' => 'Thank you for your Reviews about the product, Hoping to see you again']);
    } else {
        error_log("Error submitting review: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Error submitting review']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
exit();
?>