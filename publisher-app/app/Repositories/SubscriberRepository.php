<?php


namespace App\Repositories;

use App\Models\Subscriber;
use App\Traits\ModelRepositoryTrait;
use App\Interfaces\ModelRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SubscriberRepository implements ModelRepositoryInterface {
    use ModelRepositoryTrait;
    private $modelInstance;

    public function __construct(Subscriber $subscriber){
        $this->modelInstance = $subscriber;
    }

    public function findByName($subscriberUrl): ?Object {
        return $this->modelInstance::where('subscriber_url', $subscriberUrl)->first();
    }

    public function subscribeToTopic(Object $subscriberObject, int $topicId): bool {
        try{
            $subscriberObject->topic()->attach([$topicId]);
            return true;
        }catch(\Exception $e) {
            return false;
        }
        
    }

    public function getSubscriberWithTopicRelations(int $topicId, int $subscriberId):?Object  {
        return $this->modelInstance::with(['topic' => function($query) use ($topicId){
                                                 $query->where('topic_id', $topicId);
                                            }])
                                    ->where('id', $subscriberId)
                                    ->first();
    }
}
