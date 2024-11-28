<?php
$image_directory = 'customer/customer_image/';
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

// Get all files in the directory
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
        }
        .profile-image {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="image-grid">
        <?php foreach ($images as $image): ?>
            <img src="<?php echo htmlspecialchars($image_directory . $image); ?>" 
                 alt="<?php echo htmlspecialchars($image); ?>" 
                 class="profile-image">
        <?php endforeach; ?>
    </div>
</body>
</html>