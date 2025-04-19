<?php
class SonusAIChat {
    private $base_url = "https://chat.sonus.ai/sonus/chat.php";
    private $headers;

    public function __construct() {
        $this->headers = [
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.9",
            "Connection: keep-alive",
            "DNT: 1",
            "Origin: https://chat.sonus.ai",
            "Referer: https://chat.sonus.ai/sonus/"
        ];
    }

    public function sendMessage($message, $history = [], $reasoning = false, $model = "pro") {
        $ch = curl_init($this->base_url);
        
        $boundary = uniqid();
        $this->headers[] = 'Content-Type: multipart/form-data; boundary=' . $boundary;
        
        $post_fields = $this->buildMultipartData([
            'message' => $message,
            'history' => json_encode($history),
            'reasoning' => $reasoning ? 'true' : 'false',
            'model' => $model,
            'id' => uniqid()
        ], $boundary);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) {
            $lines = explode("\n", $data);
            foreach ($lines as $line) {
                if (strpos($line, 'data: ') === 0) {
                    $jsonData = json_decode(substr($line, 6), true);
                    if ($jsonData) {
                        if (isset($jsonData['content'])) {
                            echo "data: " . json_encode(['type' => 'content', 'content' => $jsonData['content']]) . "\n\n";
                        } elseif (isset($jsonData['cid'])) {
                            echo "data: " . json_encode(['type' => 'cid', 'cid' => $jsonData['cid']]) . "\n\n";
                        } elseif (isset($jsonData['think'])) {
                            echo "data: " . json_encode(['think' => $jsonData['think']]) . "\n\n";
                        }
                        ob_flush();
                        flush();
                    }
                }
            }
            return strlen($data);
        });

        curl_exec($ch);
        curl_close($ch);
    }

    private function buildMultipartData($data, $boundary) {
        $output = "";
        foreach ($data as $key => $value) {
            $output .= "--" . $boundary . "\r\n";
            $output .= "Content-Disposition: form-data; name=\"" . $key . "\"\r\n\r\n";
            $output .= $value . "\r\n";
        }
        $output .= "--" . $boundary . "--\r\n";
        return $output;
    }
}
?>
