<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class NotifySubscriber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $topicId;
    private $message;
    private $topicName;
    private $dateOfPublication;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $topicId, string $topicName, string $message, string $dateOfPublication)
    {
        $this->topicId = $topicId;
        $this->topicName = $topicName;
        $this->message = $message;
        $this->dateOfPublication = $dateOfPublication;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    }
}
