<?php
// Import necessary classes
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;

// Send Notification
function sendNotification($title, $body, $token)
{
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

    // Send the message
    $messaging->send($message);

    // Return the data back to the controller
    // return [
    //     "success" => true,
    //     "message" => "Notification sent successfully",
    //     "data" => $message,
    // ];
}
