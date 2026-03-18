<?php

namespace App\Console\Commands;

use App\Models\TodoTask;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\MessagingException; // Catch general Firebase Messaging exceptions

class TodoReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:todo-reminder-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send todo reminder push notification to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $complaints = TodoTask::with('todoDetail', 'todoDetail.todoUser', 'userDetails')
            ->where(['date' => date('Y-m-d'), 'time' => date('H:i')])
            ->get();

        if ($complaints) {
            foreach ($complaints as $task) {
                $firebase_credential = (new Factory)->withServiceAccount(base_path('om-satya-aac05-firebase-adminsdk-8kt8d-eba2608fc8.json'));
                $messaging = $firebase_credential->createMessaging();

                // Todo user
                if (!empty($task->todoDetail->title) && !empty($task->todoDetail->todoUser->device_token)) {
                    $message = CloudMessage::fromArray([
                        'notification' => [
                            'title' => 'Todo Reminder',
                            'body' => $task->todoDetail->title,
                        ],
                        'priority' => 'high',
                        'token' => $task->todoDetail->todoUser->device_token
                    ]);

                    try {
                        $messaging->send($message);
                        Log::info('Todo user notification sent to device: ' . $task->todoDetail->todoUser->device_token);
                    } catch (MessagingException $e) {
                        Log::error('Failed to send todo user notification to device: ' . $task->todoDetail->todoUser->device_token . ' - ' . $e->getMessage());
                    }
                }

                // Todo task user
                if (!empty($task->todoDetail->title) && !empty($task->userDetails->device_token)) {
                    $message = CloudMessage::fromArray([
                        'notification' => [
                            'title' => 'Todo Reminder',
                            'body' => $task->todoDetail->title,
                        ],
                        'priority' => 'high',
                        'token' => $task->userDetails->device_token
                    ]);

                    try {
                        $messaging->send($message);
                        Log::info('Todo User Detail Notification sent to device: ' . $task->userDetails->device_token);
                    } catch (MessagingException $e) {
                        Log::error('Failed to send todo user notification to device: ' . $task->userDetails->device_token . ' - ' . $e->getMessage());
                    }
                }
            }
        }

        Log::info('Try to send todo notification...');
    }
}
