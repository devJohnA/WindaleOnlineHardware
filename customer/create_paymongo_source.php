<?php
require_once("../include/initialize.php");
if(!isset($_SESSION['CUSID'])) {
    redirect(web_root."index.php");
}

// PayMongo Secret Key
$secret_key = "sk_test_YZqncZM8k8WrVAY6Nyky8yaf";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get order details from the form
    $orderNum = $_POST['ORDEREDNUM'] ?? '';
    $totalAmount = $_POST['alltot'] ?? ''; // This should already include the delivery fee
    $deliveryFee = $_POST['PLACE'] ?? '0';
    $customerName = $_POST['customername'] ?? '';
    $customerEmail = $_POST['customeremail'] ?? '';
    $customerno = $_POST['customerno'] ?? '';

    // Store delivery fee in session for later use
    $_SESSION['delivery_fee'] = $deliveryFee;

    // Validate required fields
    if (empty($orderNum) || empty($totalAmount) || empty($customerName) || empty($customerEmail)) {
        error_log("Missing required fields: OrderNum: $orderNum, Amount: $totalAmount, Name: $customerName, Email: $customerEmail");
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields'
        ]);
        exit;
    }

    // Validate and convert amount to centavos
    if (!is_numeric($totalAmount) || $totalAmount <= 0) {
        error_log("Invalid amount: $totalAmount");
        echo json_encode([
            'success' => false,
            'message' => 'Invalid amount'
        ]);
        exit;
    }
    
    $amount = intval($totalAmount * 100);

    // Get the current domain and protocol
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $domain = $_SERVER['HTTP_HOST'];

    // Construct absolute URLs for success and failed redirects
    $successUrl = $protocol . "://" . $domain . web_root . "customer/process_gcash_payment.php?order=" . urlencode($orderNum);
    $failedUrl = $protocol . "://" . $domain . web_root . "customer/process_gcash_payment.php?order=" . urlencode($orderNum) . "&status=failed";

    try {
        // Prepare the payload
        $payload = json_encode([
            'data' => [
                'attributes' => [
                    'amount' => $amount,
                    'redirect' => [
                        'success' => $successUrl,
                        'failed' => $failedUrl
                    ],
                    'billing' => [
                        'name' => $customerName,
                        'email' => $customerEmail,
                        'phone' => $customerno
                    ],
                    'type' => 'gcash',
                    'currency' => 'PHP'
                ]
            ]
        ]);
        
        error_log("PayMongo API Request Payload: " . $payload);

        // Create a PayMongo source for GCash
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.paymongo.com/v1/sources");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode($secret_key)
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if(curl_errno($ch)){
            error_log('Curl error: ' . curl_error($ch));
        }
        
        curl_close($ch);

        // Log the full response and HTTP code for debugging
        error_log("PayMongo API Response: " . $response);
        error_log("HTTP Code: " . $httpCode);

        $result = json_decode($response, true);

        // Log the entire result for debugging
        error_log("Decoded PayMongo Response: " . print_r($result, true));

        if ($httpCode == 200 && isset($result['data']['id'])) {
            // Store the source ID in the session for later use
            $_SESSION['paymongo_source_id'] = $result['data']['id'];

            // Check if checkout_url exists in the response
            if (isset($result['data']['attributes']['redirect']['checkout_url'])) {
                $checkoutUrl = $result['data']['attributes']['redirect']['checkout_url'];
                echo json_encode([
                    'success' => true,
                    'checkoutUrl' => $checkoutUrl
                ]);
            } else {
                error_log("Checkout URL not found in the response");
                echo json_encode([
                    'success' => false,
                    'message' => 'Checkout URL not found in the response'
                ]);
            }
        } else {
            $errorMessage = isset($result['errors'][0]['detail']) ? $result['errors'][0]['detail'] : 'Unknown error occurred';
            $errorCode = isset($result['errors'][0]['code']) ? $result['errors'][0]['code'] : 'Unknown';
            error_log("PayMongo Error: Code - " . $errorCode . ", Message - " . $errorMessage);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create GCash source: ' . $errorMessage,
                'errorCode' => $errorCode
            ]);
        }
    } catch (Exception $e) {
        error_log("PayMongo Error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>