<?php
class Sms {
    private $apiKey = 'TA_CLE';
    private $sender = SITE_NAME;
    private $apiUrl = 'https://api.smsprovider.com/send';

    public function send($number, $message)
    {
        $data = [
            'to' => $number,
            'message' => $message,
            'sender' => $this->sender,
            'api_key' => $this->apiKey
        ];

        $response = file_get_contents($this->apiUrl . '?' . http_build_query($data));
        return json_decode($response);
    }
}