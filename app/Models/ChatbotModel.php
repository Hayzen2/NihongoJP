<?php
namespace App\Models;
class ChatbotModel {
    private string $apiKey;
    public function __construct() {
        $this->apiKey = $_ENV['GEMINI_API_KEY'];
    }

    public function sendMessage($message, $history = []) {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
        //track the history of the conversation
        $contents = [];

        //add the history context to the conversation
        foreach ($history as $historyMessage) {
            if (is_array($historyMessage) && isset($historyMessage['message'])) {
                $contents[] = [
                    "parts" => [
                        ["text" => $historyMessage['message']]
                    ]
                ];
            }
        }

        //add the new message to the conversation
        $contents[] = [
            "parts" => [
                ["text" => $message]
            ]
        ];

        // Build request body
        $data  = [
            "contents" => $contents,
        ];

        $curl = curl_init($url); // Initialize cURL
        curl_setopt($curl, CURLOPT_POST, true); // Set POST method
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // Set POST data
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return response
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "X-goog-api-key: " . $this->apiKey
        ]); // Set headers

        $response = curl_exec($curl); // Execute cURL request

        if($response === false){
            $error = curl_error($curl);
            curl_close($curl);
            return "Error calling API: " . $error;
        }
        curl_close($curl);

        $result = json_decode($response, true); // Decode JSON response
        // Extract bot reply safely
        return $result['candidates'][0]['content']['parts'][0]['text'] ?? "Sorry, I couldn't get a response.";

        //reply format
        /*
        {
            "candidates": [
                {
                    "content": {
                        "parts": [
                            {
                                "text": "Hi! How can I help you?"
                            }
                        ]
                    }
                }
            ]
        }
        */
    }
}