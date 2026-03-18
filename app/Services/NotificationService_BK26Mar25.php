<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\MessagingException; // Catch general Firebase Messaging exceptions
use Illuminate\Support\Facades\Log; // Optional: to log errors

class NotificationService
{
    public function sendNotification($title, $body, $token)
    {
        try {
            $firebase_credential = (new Factory)->withServiceAccount(base_path('om-satya-aac05-firebase-adminsdk-8kt8d-eba2608fc8.json'));
            $messaging = $firebase_credential->createMessaging();

            $message = CloudMessage::fromArray([
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'priority' => 'high',
                'token' => $token,
            ]);

            $messaging->send($message);
        } catch (MessagingException $e) {
            // Log the error for debugging purposes (optional)
            Log::error('Firebase notification failed: ' . $e->getMessage());

            // You can choose to return a response or just continue without stopping execution
        }
    }
}
