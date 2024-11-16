<?php
$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
class SMSGateway {
    private $apiUrl;
    private $apiKey;

    public function __construct() {
        $this->apiUrl = 'https://8kr329.api.infobip.com/sms/2/text/advanced';
        $this->apiKey = 'a374af3847716d0e3a6c734ef37d92ea-346dfccd-687d-4ee7-8783-f8408b544591';
    }

    public function sendSMS($phone, $message) {
        try {
            $data = [
                "messages" => [
                    [
                        "destinations" => [
                            ["to" => $phone]
                        ],
                        "text" => $message
                    ]
                ]
            ];

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [
                    'Authorization: App ' . $this->apiKey,
                    'Content-Type: application/json'
                ]
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                error_log('Infobip SMS Error: ' . curl_error($ch));
                return false;
            }

            curl_close($ch);

            $result = json_decode($response, true);
            if (isset($result['messages'][0]['status']['groupName']) &&
                $result['messages'][0]['status']['groupName'] === "PENDING") {
                return true;
            }

            error_log('Infobip SMS Error: ' . $response);
            return false;
        } catch (Exception $e) {
            error_log('Infobip SMS Exception: ' . $e->getMessage());
            return false;
        }
    }
}

function sendSMS($phone, $message) {
    $smsGateway = new SMSGateway();

    // Ensure correct format: +63XXXXXXXXX
    if (substr($phone, 0, 1) === '0') {
        $phone = '+63' . substr($phone, 1);
    }

    return $smsGateway->sendSMS($phone, $message);
}

?>