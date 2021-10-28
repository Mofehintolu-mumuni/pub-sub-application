<?php


namespace App\Repositories;

use App\Models\SubscribedTopic;
use App\Traits\ModelRepositoryTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Interfaces\ModelRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SubscribedTopicRepository implements ModelRepositoryInterface {
    use ModelRepositoryTrait;
    private $modelInstance;

    public function __construct(SubscribedTopic $topic){
        $this->modelInstance = $topic;
    }

    public function findByTopicId($topicId): ?Object{
        return $this->modelInstance::where('topic_id', $topicId)->first();
    }


}
