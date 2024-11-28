<?php
// Full server path to the image directory
$image_directory = $_SERVER['DOCUMENT_ROOT'] . '/customer/customer_image/';
$web_path = '/customer/customer_image/';
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

// Verify directory exists
if (!is_dir($image_directory)) {
    die("Image directory does not exist: " . $image_directory);
}

// Get all image files
$images = array_filter(scandir($image_directory), function($file) use ($allowed_extensions) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return $file != '.' && $file != '..' && in_array($ext, $allowed_extensions);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Images</title>
    <style>
        .image-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        .profile-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="image-grid">
        <?php 
        if (!empty($images)) {
            foreach ($images as $image): 
        ?>
            <img src="<?php echo htmlspecialchars($web_path . $image); ?>"
                 alt="<?php echo htmlspecialchars($image); ?>"
                 class="profile-image">
        <?php 
            endforeach; 
        } else {
            echo "<p>No images found.</p>";
        }
        ?>
    </div>
</body>
</html>