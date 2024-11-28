<?php
$image_directory = 'customer/customer_image/';
$files = glob($image_directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

foreach($files as $file): ?>
    <img src="<?php echo htmlspecialchars($file); ?>" 
         alt="Profile Image" 
         class="profile-image"
         onerror="console.log('Failed to load: ' + this.src);">
<?php endforeach; ?>