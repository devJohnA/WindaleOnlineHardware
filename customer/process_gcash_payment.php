<?php
require_once("../include/initialize.php");

function processGcashPayment($orderNum, $status) {
    global $mydb;
    
    $source_id = $_SESSION['paymongo_source_id'] ?? '';
    if (empty($source_id)) {
        error_log("PayMongo source ID not found in session");
        return false;
    }
    
    $secret_key = "sk_test_YZqncZM8k8WrVAY6Nyky8yaf";
    
    // First, verify the source status
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.paymongo.com/v1/sources/$source_id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic " . base64_encode($secret_key)
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($result['data']['attributes']['status'] === 'chargeable' || $status === 'success') {
        // Source is chargeable, now create a payment
        $payload = json_encode([
            'data' => [
                'attributes' => [
                    'amount' => $result['data']['attributes']['amount'],
                    'source' => [
                        'id' => $source_id,
                        'type' => 'source'
                    ],
                    'currency' => 'PHP',
                    'description' => 'Online Payment'
                ]
            ]
        ]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.paymongo.com/v1/payments");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic " . base64_encode($secret_key),
            "Content-Type: application/json"
        ]);
        
        $payment_response = curl_exec($ch);
        curl_close($ch);
        
        $payment_result = json_decode($payment_response, true);
        
        if ($payment_result['data']['attributes']['status'] === 'paid') {
            // Payment was successful, create the order
            $summary = new Summary();
            $summary->ORDEREDNUM = $orderNum;
            $summary->ORDEREDDATE = date('Y-m-d H:i:s');
            $summary->CUSTOMERID = $_SESSION['CUSID'];
            $summary->PAYMENTMETHOD = 'GCash';
            $summary->ORDEREDSTATS = 'Pending';
            $summary->ORDEREDREMARKS = 'Your order is on process.';
            $summary->CLAIMEDDATE = date('Y-m-d H:i:s', strtotime('+3 days'));
            $summary->HVIEW = 0;
            $summary->PAYMENT = $payment_result['data']['attributes']['amount'] / 100; // Overall total in pesos
            $summary->DELFEE = $_SESSION['delivery_fee'] ?? 0; // Delivery fee
            $summary->Gcashpaymentstatus = 'Paid';
            $summary->create();
            
            // Process each item in the cart
            if (isset($_SESSION['gcCart'])) {
                foreach ($_SESSION['gcCart'] as $cart) {
                    $order = new Order();
                    $order->PROID = $cart['productid'];
                    $order->ORDEREDQTY = $cart['qty'];
                    $order->ORDEREDPRICE = $cart['price'];
                    $order->ORDEREDNUM = $orderNum;
                    $order->create();
                    
                    // Deduct quantity from product
                    $product = new Product();
                    $product->qtydeduct($cart['productid'], $cart['qty']);
                }
            }
            
            // Update the autonumber
            $autonumber = New Autonumber();
            $autonumber->auto_update('ordernumber');
            
            // Clear the cart and order details from session
            unset($_SESSION['gcCart']);
            unset($_SESSION['orderdetails']);
            unset($_SESSION['paymongo_source_id']);
            unset($_SESSION['delivery_fee']);
            
            return true;
        } else {
            error_log("GCash payment failed. Status: " . $payment_result['data']['attributes']['status']);
            return false;
        }
    } else {
        error_log("GCash source not chargeable. Status: " . $result['data']['attributes']['status']);
        return false;
    }
}

if (isset($_GET['order'])) {
    $orderNum = $_GET['order'];
    $status = $_GET['status'] ?? 'success';
    
    if (processGcashPayment($orderNum, $status)) {
        message("Your GCash payment was successful and your order has been created!", "success");
        redirect(web_root . "index.php?q=profile");
    } else {
        message("GCash payment was not successful. Please try again.", "error");
        redirect(web_root . "index.php?q=cart");
    }
} else {
    message("Invalid request.", "error");
    redirect(web_root . "index.php?q=cart");
}
?>