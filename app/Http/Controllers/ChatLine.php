<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatLine extends Controller
{
    public $channelToken = 'rXAqVM4Sst0WA8i3aSmEAhKcwqdY4o4zO+dOkzAY3E/h7Ykx1FOUaXdE2avc1a4IvsOW9EntkqXExh0DN4BHBzMv5gLBRXjdyIiVMh3wP4Ff5yMIRMM+t7zDE2Umab5h2ka39GPidW3dzYxqS2Q0NgdB04t89/1O/w1cDnyilFU=';
    public $group_id = 'Ca15d5804ebf27251b427533420e27e6f';

    public function send(Request $request)
    {
        $input = $request->input();
    }

    public function sendGps($data)
    {
        $messageData = [
            'to' => $this->group_id,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $data['text']
                ],
                [
                    'type' => 'image',
                    'originalContentUrl' => $data['photo'],
                    'previewImageUrl' => $data['photo']
                ]
            ]
        ];

        $ch = curl_init('https://api.line.me/v2/bot/message/push');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelToken
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));

        $result = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus == 200) {
            return true;
        } else {
            return false;
        }
    }
}
