<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException; // Catch general Firebase Messaging exceptions
use Illuminate\Support\Facades\Log; // Optional: to log errors

//  {
//     "to": "<FCM_TOKEN>",
//     "data": {
//       "screen": "/notification-details",
//   "to_do_id":0,
//     },
//     "notification": {
//       "title": "New Message",
//       "body": "Click to open details"
//     }
//   }

class NotificationService
{
    public function sendTodoNotification($title, $body, $token, $type, $id)
    {
        try {
            $firebase_credential = (new Factory)->withServiceAccount(base_path('om-satya-aac05-firebase-adminsdk-8kt8d-eba2608fc8.json'));
            $messaging = $firebase_credential->createMessaging();

            $message = CloudMessage::fromArray([
                'data' => [
                    'screen' => '/notification-details',
                    'id' => $id,
                    'type' => $type,
                ],
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

    public function sendAlarmNotificationold($title, $body, $token, $type, $id)
    {
        try {
            $firebase_credential = (new Factory)->withServiceAccount(base_path('om-satya-aac05-firebase-adminsdk-8kt8d-eba2608fc8.json'));
            $messaging = $firebase_credential->createMessaging();

            $message = CloudMessage::fromArray([
                'data' => [
                    'screen' => '/complaint-alarmnotification',
                    'id' => $id,
                    'type' => $type,
                ],
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

    public function sendAlarmNotification($title, $body, $token, $type, $id)
    {
        try {
            $firebase_credential = (new Factory)->withServiceAccount(base_path('om-satya-aac05-firebase-adminsdk-8kt8d-eba2608fc8.json'));
            $messaging = $firebase_credential->createMessaging();

            // $message = CloudMessage::fromArray([
            //     'to' => 'f6pcToEtTtOZ-5XZGO1_1t:APA91bHFjz2j2JUaxPlNPNLvqQCHTQQDk77kiGDFlmGpnmC9bgkyITYBcML5QaE-GjC5dLFZuHnESiczjC3aUlEDbHqlab6-AGA4RR0DzdLLujmNZI1Wsao',
            //     'priority' => 'high',
            //     'data' => [
            //         'type' => 'INCOMING_CALL',
            //         'call_id' => 'call_123456',
            //         'caller_name' => 'John Doe'
            //     ]
            // ]);

            // $message = CloudMessage::fromArray([
            //             'token' => $token,
            //             'android' => [
            //                 'priority' => 'high',
            //             ],
            //             'data' => [
            //                 'type'        => 'INCOMING_CALL',
            //                 'call_id'     => $title,
            //                 'caller_name' => $body,
            //             ],
            //         ]);

            //  $message = CloudMessage::fromArray([
            //             'token' => $token,
            //             'android' => [
            //                 'priority' => 'high',
            //             ],
            //             'data' => [
            //                 'type'        => 'INCOMING_CALL',
            //                 'call_id'     => 'call_123',
            //                 'id' => '98',
            //                 'screen' => '/complaint_alarm_notification',
            //                 'role' => 'engineer',
            //                 'caller_name' => 'Pickwell Support',
            //             ],
            //         ]);

            //$messaging->send($message);

    //         $tokens = [
    // 'fSeb5UJGQPKzat0hnr8N5n:APA91bE4jQGPlbH1zowEPTqdO5SW5NAwIR0XBujtvt-ec0oYRqcU02T1F1nfltlBZMoDtA-S-lWLFjcksv9IU0v1zoIt5TlipewCJzCljZwCHtF1yoEftCo',
    // 'cjhjuBoEQmG_jQ009VYQ0V:APA91bHLQnl8b85SMZ1iOjti8ZVryPeR42LMNpXkvqEsxMK4VeQKwgVSRXA-Vj9SATyoqe_G-On4qukZBtJcqfNJpFtG7l85MckP2M8BVF682BoG2N9WOEc',
    // 'c3foNFldyctFa-QEbbxaM1:APA91bFYkUKV_ET4S4CASDT2Ow-5svbPCpLO3aChKC-CddiF1_ucXmDp1hUwr7Cr9nP1U9m4Y12Rk3WycLZ5a1d6EEt7Qww80y7Bw2OzqskQzVxtlBEtgC0',
    // ];

    $tokens = $token;

            $message = CloudMessage::new()
    ->withNotification(Notification::create($title, $body))
    ->withData([
        'screen' => '/complaint_alarm_notification',
        'type'        => 'INCOMING_CALL',
        'call_id'     => 'call_123',
        'call_type' => $type,
        'id' => $id,
        'caller_name' => $title,
    ]);
            $messaging->sendMulticast($message, $tokens);
        } catch (MessagingException $e) {
            // Log the error for debugging purposes (optional)
            Log::error('Firebase notification failed: ' . $e->getMessage());

            // You can choose to return a response or just continue without stopping execution
        }
    }

    public function sendNotificationOld($title, $body, $token)
    {
        //dd($token); 
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

    public function sendNotification($title, $body, $token)
    {
        //dd($token); 
        try {
            $firebase_credential = (new Factory)->withServiceAccount(base_path('om-satya-aac05-firebase-adminsdk-8kt8d-eba2608fc8.json'));
            $messaging = $firebase_credential->createMessaging();

            $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData([
                'title' => $title,
                'body' => $body,
            ]);
            $messaging->sendMulticast($message, $token);
        } catch (MessagingException $e) {
            // Log the error for debugging purposes (optional)
            Log::error('Firebase notification failed: ' . $e->getMessage());

            // You can choose to return a response or just continue without stopping execution
        }
    }
}
