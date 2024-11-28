<?php
$image_directory = $_SERVER['DOCUMENT_ROOT'] . '/customer/customer_image/';
$web_path = '/customer/customer_image/';  // Web-accessible path

$images = array_filter(scandir($image_directory), function($file) use ($allowed_extensions) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return $file != '.' && $file != '..' && in_array($ext, $allowed_extensions);
});
?>

<div class="image-grid">
    <?php foreach ($images as $image): ?>
        <img src="<?php echo htmlspecialchars($web_path . $image); ?>"
             alt="<?php echo htmlspecialchars($image); ?>"
             class="profile-image">
    <?php endforeach; ?>
</div>