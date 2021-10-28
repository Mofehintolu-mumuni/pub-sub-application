<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait TopicTrait
{

    /**
     * Get a topic object from it's name
     * @param string topicName
     * @param object topicRepository
     * @retruns Object|null
     */
    function getTopicObjectFromName($topicName, $topicRepository): Object|null {
        $topicObject = $topicRepository->findByName(ucfirst(trim($topicName)));
        return $topicObject ?? null;
   }
}