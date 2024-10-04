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
    header("Location: error.php?message=" . urlencode("Database connection failed"));
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
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        error_log("CSRF token validation failed");
        header("Location: error.php?message=" . urlencode("Security check failed"));
        exit();
    }

    $proid = intval($_POST['proid']);
    $name = validate_input($_POST['name']);
    $rating = intval($_POST['rating']);
    $reviewtext = validate_input($_POST['reviewtext']);

    // Validate inputs
    if (empty($name) || empty($reviewtext) || $rating < 1 || $rating > 5) {
        header("Location: error.php?message=" . urlencode("Invalid input data"));
        exit();
    }

    // // Rate limiting (example: 1 review per minute)
    // if (isset($_SESSION['last_review_time']) && time() - $_SESSION['last_review_time']) {
    //     header("Location: error.php?message=" . urlencode("Please wait before submitting another review"));
    //     exit();
    // }

    $sql = "INSERT INTO tblcustomerreview (PROID, CUSTOMERNAME, RATING, REVIEWTEXT) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isis", $proid, $name, $rating, $reviewtext);

    if ($stmt->execute()) {
        $_SESSION['last_review_time'] = time();
        header("Location: index.php?q=single-item&id=" . $proid . "&message=" . urlencode("Review submitted successfully"));
    } else {
        error_log("Error submitting review: " . $stmt->error);
        header("Location: error.php?message=" . urlencode("Error submitting review"));
    }

    $stmt->close();
} else {
    header("Location: error.php?message=" . urlencode("Invalid request method"));
}

$conn->close();
exit();
?>