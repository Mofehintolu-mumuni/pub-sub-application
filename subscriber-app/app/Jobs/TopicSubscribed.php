<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Repositories\SubscribedTopicRepository;

class TopicSubscribed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $appPort;
    private $topicId;
    private $subscriberUrl;
    private $dateOfSubscription;
    private $subscribedTopicRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $topicId, string $subscriberUrl, object $dateOfSubscription)
    {
        $this->appPort = config('app')['port'];
        $this->topicId = $topicId;
        $this->dateOfSubscription = $dateOfSubscription;
        $this->subscriberUrl = $subscriberUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   //check if this is the correct subscriber
        if(strtolower(trim($this->subscriberUrl)) == strtolower(url('/')).':'.config('app')['port']) {
            //check if topic is already subscribed to
            $this->subscribedTopicRepository = \App::make(SubscribedTopicRepository::class);

            if(is_null($this->subscribedTopicRepository->findByTopicId($this->topicId))) {
            $data = [
                    'topic_id' => $this->topicId,
                    'date_subscribed' => $this->dateOfSubscription
                    ];
   
            if($this->subscribedTopicRepository->create($data)) {
                Log::info("You have been successfully subscribed to a topic");
            }else{
                Log::error("Subscription to topic was not successfull.");
            }
         }else{
             dump("You are already sybscribed to this topic.");
         }


        }
       


    }
}
