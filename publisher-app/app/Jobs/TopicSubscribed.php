<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class TopicSubscribed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $topicId;
    private $subscriberUrl;
    private $dateOfSubscription;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $topicId, string $subscriberUrl, object $dateOfSubscription)
    {
        $this->topicId = $topicId;
        $this->subscriberUrl = $subscriberUrl;
        $this->dateOfSubscription = $dateOfSubscription;
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
