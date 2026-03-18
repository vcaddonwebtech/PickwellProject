<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Messaging\MessageTarget;
use Maatwebsite\Excel\Concerns\FromArray;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Send push notification
        $title = 'Test title';
        $body = 'Test body';
        $token = 'dkiVrZLPTamZxDH-GaHEkI:APA91bEmFGxlv2TMABzO2qLIK5F3PDqDrGmaPfk3UO5ALDjkSas8lzCYa_dS9AEXy3jksV4P8eFpagzn9G0PArQ43NkljfW0fr8krGScYq9m0qIAQGOe6l07FwXnSj3TUO20gO4zd24n';
        // dd(Auth::user()->device_token);

        if ($title != "" && $body != "" && $token != "") {
            // Get the response from the helper function
            $notificationResponse = sendNotification($title, $body, $token);

            // Return the response to Postman or any other client
            return response()->json($notificationResponse, 200);
        }

        // $firebase_credential = (new Factory)->withServiceAccount(base_path('om-satya-aac05-firebase-adminsdk-8kt8d-eba2608fc8.json'));
        // $messaging = $firebase_credential->createMessaging();
        // $message = CloudMessage::fromArray([
        //     'notification' => [
        //         'title' => 'Test notification',
        //         'body' => 'This is a test notification',
        //     ],
        //     'priority' => 'high',
        //     // 'topic' => 'global',
        //     'token' => 'dkiVrZLPTamZxDH-GaHEkI:APA91bEmFGxlv2TMABzO2qLIK5F3PDqDrGmaPfk3UO5ALDjkSas8lzCYa_dS9AEXy3jksV4P8eFpagzn9G0PArQ43NkljfW0fr8krGScYq9m0qIAQGOe6l07FwXnSj3TUO20gO4zd24n',
        // ]);
        // $messaging->send($message);

        // return response()->json([
        //     "success" => true,
        //     "message" => "Notification sent successfully",
        //     "data" => $message,
        // ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
