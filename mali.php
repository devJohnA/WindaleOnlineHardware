<?php
$image_directory = 'customer/customer_image/';
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

$images = array_filter(scandir($image_directory), function($file) use ($allowed_extensions) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return $file != '.' && $file != '..' && in_array($ext, $allowed_extensions);
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>Uploaded Images</title>
    <style>
        .image-grid { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 10px; 
        }
        .profile-image { 
            width: 200px; 
            height: 200px; 
            object-fit: cover; 
        }
    </style>
</head>
<body>
    <div class="image-grid">
        <?php foreach ($images as $image): ?>
            <img src="<?= htmlspecialchars($image_directory . $image) ?>" 
                 class="profile-image">
        <?php endforeach; ?>
    </div>
</body>
</html>