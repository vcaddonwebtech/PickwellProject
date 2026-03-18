<?php

namespace App\Console\Commands;

use App\Models\SalesPersonsTask;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\MessagingException; // Catch general Firebase Messaging exceptions

class SalseReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:salse-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $salseDetail = SalesPersonsTask::with('salseTodo', 'salseTodo.salseUserDetail', 'salseTodo.saleAssignUserDetail')
            ->where(['date' => date('Y-m-d'), 'time' => date('H:i')])
            ->get();

        if ($salseDetail) {
            foreach ($salseDetail as $salse) {
                $firebase_credential = (new Factory)->withServiceAccount(base_path('om-satya-aac05-firebase-adminsdk-8kt8d-eba2608fc8.json'));
                $messaging = $firebase_credential->createMessaging();

                // salse_user_detail
                if (!empty($salse->comment_first) && !empty($salse->salseTodo->salseUserDetail->device_token)) {
                    $message = CloudMessage::fromArray([
                        'notification' => [
                            'title' => 'Salse Reminder',
                            'body' => $salse->comment_first,
                        ],
                        'priority' => 'high',
                        'token' => $salse->salseTodo->salseUserDetail->device_token
                    ]);

                    try {
                        $messaging->send($message);
                        Log::info('Notification sent to device: ' . $salse->salseTodo->salseUserDetail->device_token);
                    } catch (MessagingException $e) {
                        Log::error('Failed to send notification to device: ' . $salse->salseTodo->salseUserDetail->device_token . ' - ' . $e->getMessage());
                    }
                }

                // sale_assign_user_detail
                if (!empty($salse->comment_first) && !empty($salse->salseTodo->saleAssignUserDetail->device_token)) {
                    $message = CloudMessage::fromArray([
                        'notification' => [
                            'title' => 'Salse Reminder',
                            'body' => $salse->comment_first,
                        ],
                        'priority' => 'high',
                        'token' => $salse->salseTodo->saleAssignUserDetail->device_token
                    ]);

                    try {
                        $messaging->send($message);
                        Log::info('Notification sent to device: ' . $salse->salseTodo->saleAssignUserDetail->device_token);
                    } catch (MessagingException $e) {
                        Log::error('Failed to send notification to device: ' . $salse->salseTodo->saleAssignUserDetail->device_token . ' - ' . $e->getMessage());
                    }
                }
            }
        }

        Log::info('Try to send salse reminder notification...');
    }
}
