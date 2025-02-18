<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SMSPohController extends Controller
{
    //sms_test
    public function sms_test(){
        $api_key = env('SMSPOH_KEY');
        $api_secret = env('SMSPOH_SECRET');
        $sender_id = 'ZVZ';
        $recipient = '+959422826537';
        $message = 'Hey Baby Lay Dink Coffee Or Eat Something';
    
        $api_url = 'https://v3.smspoh.com/api/rest/send';
    
        // Prepare the payload
        $payload = [
            'to' => $recipient,
            'message' => $message,
            'from' => $sender_id
        ];
    
        // Encode the API key and secret
        $api_credentials = base64_encode("$api_key:$api_secret");
    
        // Set the headers
        $headers = [
            "Authorization: Bearer $api_credentials",
            'Content-Type: application/json'
        ];
    
        // Initialize cURL
        $ch = curl_init($api_url);
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    
        // Execute the request
        $response = curl_exec($ch);
    
        // Check for errors
        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            $response_data = json_decode($response, true);
            if (isset($response_data['messages'])) {
                echo 'Message sent successfully!';
            } else {
                echo 'Failed to send message. Error: ' . $response_data['message'];
            }
        }
    
        // Close cURL session
        curl_close($ch);

        echo "Success";
    }
}
