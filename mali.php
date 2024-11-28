<?php
$image_directory = $root_path . '/customer/customer_image/';
$files = glob($image_directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

foreach($files as $file): 
    $web_path = '/customer/customer_image/' . basename($file);
?>
    <img src="<?php echo htmlspecialchars($web_path); ?>" 
         alt="Profile Image" 
         class="profile-image"
         onerror="console.log('Failed to load: ' + this.src);">
<?php endforeach; ?>
