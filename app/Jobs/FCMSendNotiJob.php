<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\CloudMessage;

class FCMSendNotiJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $title, public string $description, public string $token, public array $data) { }

    /**
     * Execute the job.
     */
    public function handle(Messaging $messaging): void
    {
        Log::info(__METHOD__, [
            'message' => '==== FCm JOB RUN====',
        ]);
        $message = CloudMessage::new()
            ->withNotification(Notification::create($this->title, $this->description))
            ->withData($this->data)
            ->toToken($this->token);

        $messaging->send($message);
    }
}
