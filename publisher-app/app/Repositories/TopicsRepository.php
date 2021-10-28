<?php


namespace App\Repositories;

use App\Models\Topic;
use App\Traits\ModelRepositoryTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Interfaces\ModelRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TopicsRepository implements ModelRepositoryInterface {
    use ModelRepositoryTrait;
    private $modelInstance;

    public function __construct(Topic $topic){
        $this->modelInstance = $topic;
    }

    public function findByName($topicName): ?Object{
        return $this->modelInstance::where('title', $topicName)->first();
    }

    public function findById($topicId): ?Object{
        return $this->modelInstance::where('id', $topicId)->first();
    }

    public function getTopicWithMessageAndSubscriberRelations(int $topicId, int $messageId):Collection {
        return $this->modelInstance::with(['message' => function($query) use ($messageId){
                                                        $query->where('id', $messageId);
                                            }, 'subscriber' => function($query) use ($topicId){
                                                 $query->where('subscribers_topics.topic_id', $topicId);
                                            }])
                                    ->where('id', $topicId)
                                    ->get();
    }

    public function listTopic():LengthAwarePaginator {
        return $this->modelInstance::with(['message','subscriber'])
                                    ->paginate();
    }


}
