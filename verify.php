<?php
if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];
    $customer = new Customer();
    $customer->find_by_verificationcode($verification_code);
    if ($customer) {
        $customer->VERIFIED = 1;
        $customer->update();
        header('Location: ' . web_root . 'index.php?q=product');
        exit;
    } else {
        echo 'Invalid verification code.';
    }
}
?>
