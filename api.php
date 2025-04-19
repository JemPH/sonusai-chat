<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');

require_once 'SonusAIChat.php';

function sendSSE($data) {
    echo "data: " . json_encode($data) . "\n\n";
    ob_flush();
    flush();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $message = $data['message'] ?? '';
    $history = $data['history'] ?? [];
    $model = $data['model'] ?? 'pro';
    $reasoning = $data['reasoning'] ?? false;
    
    if (!empty($message)) {
        $chat = new SonusAIChat();
        $response = $chat->sendMessage($message, $history, $reasoning, $model);
        
        if ($response) {
            sendSSE([
                'type' => 'message',
                'content' => $response['message'],
                'conversation_id' => $response['conversation_id']
            ]);
        } else {
            sendSSE([
                'type' => 'error',
                'content' => 'Failed to get response from AI'
            ]);
        }
    }
}
?>
