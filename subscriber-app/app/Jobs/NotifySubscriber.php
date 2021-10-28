<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Repositories\SubscribedTopicRepository;

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
        $this->message = $message;
        $this->topicName = $topicName;
        $this->dateOfPublication = $dateOfPublication;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //check if topic has been subscribed to

        $this->subscribedTopicRepository = \App::make(SubscribedTopicRepository::class);

        if(!is_null($this->subscribedTopicRepository->findByTopicId($this->topicId))) {
            //print payload
            dump("Info : A message has been published for topic: {$this->topicName}");
            dump("Message: {$this->message} | Date: {$this->dateOfPublication}");
        }
    }
}