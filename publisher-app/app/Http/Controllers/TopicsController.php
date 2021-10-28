<?php

namespace App\Http\Controllers;

use App\Traits\TopicTrait;
use Illuminate\Http\Request;
use App\Jobs\TopicSubscribed;
use App\Jobs\NotifySubscriber;
use Illuminate\Http\JsonResponse;
use App\Interfaces\HttpStatusCode;
use Illuminate\Support\Facades\DB;
use App\Repositories\TopicsRepository;
use App\Http\Requests\CreateTopicRequest;
use App\Repositories\SubscriberRepository;
use App\Repositories\MessagesRepository;
use App\Services\ApiJsonResponserService;
use App\Http\Requests\SubscribeToTopicRequest;
use App\Http\Requests\PublishMessageToTopicRequest;


class TopicsController extends Controller
{
    use TopicTrait;

    private $topicsRepository;
    private $messagesRepository;
    private $subscriberRepository;

    public function __construct(TopicsRepository $topicsRepository, MessagesRepository $messagesRepository, SubscriberRepository $subscriberRepository)
    {
        $this->topicsRepository = $topicsRepository;
        $this->messagesRepository = $messagesRepository;
        $this->subscriberRepository = $subscriberRepository;
    }

/**
 * This function is used to create a topic sent via CreateTopicRequest $request
 * @param CreateTopicRequest $request
 * @return Illuminate\Http\JsonResponse
 */
/** 
     * @OA\Post(
     *     path="/api/create-topic",
     *     summary="create a topic| route('create-topic')",
     *     description="create a topic ",
     *     tags={"Publisher app"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 @OA\Property(property="topic",type="string"),
     *                 example={
     *                      "topic": "Topic title"
     *                  }
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
    public function createTopic(CreateTopicRequest $request) {

        $createdTopicObject = $this->topicsRepository->create(['title' => $request->topic]);

        if(is_null($createdTopicObject)) {
            return ApiJsonResponserService::sendData(HttpStatusCode::OK, 'Topic was not created successfully.', []);
        }

        return ApiJsonResponserService::sendData(HttpStatusCode::CREATED, 'Topic was created successfully.', $createdTopicObject);
    }


    /**
     * This function is used to publish a message for a particular topic to subscribers
     * @param PublishMessageToTopicRequest $request
     * @return Illuminate\HTTP\JsonResponse
     */
    /** 
     * @OA\Post(
     *     path="/api/publish/{topicId}",
     *     summary="Publish message for a topic | route('/publish/{topicId}')",
     *     description="Publish message for a topic ",
     *     tags={"Publisher app"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 @OA\Property(property="message",type="string"),
     *                 example={
     *                      "message": "Hello world"
     *                  }
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
    public function publishMessageToTopic(PublishMessageToTopicRequest $request){
        //save message to database
        $savedMessageObject = $this->messagesRepository->create(['topic_id' => request()->topic_id, 'message' => $request->message]);
        
        if(is_null($savedMessageObject)) {
            return ApiJsonResponserService::sendData(HttpStatusCode::OK, 'Topic was not published successfully.', []);
        }

        //dispatch job to handle publishing to subscribers in background process.
        NotifySubscriber::dispatch(request()->topic_id, request()->topic_title, $request->message, request()->created_at)->onQueue('subscribers');

        return ApiJsonResponserService::sendData(HttpStatusCode::OK, 'Topic was published successfully.', ["topic" => request()->topic_title, "data" => $request->message]);
    }


    /**
     * This function is used to subscribe a url to a topic 
     * @param SubscribeToTopicRequest $request
     * @return Illuminate\HTTP\JsonResponse
     */
    /** 
     * @OA\Post(
     *     path="/api/subscribe/{topicId}",
     *     summary="Subscribe a topic| route('/subscribe/{topicId}')",
     *     description="Subscribe to a topic ",
     *     tags={"Publisher app"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 @OA\Property(property="url",type="string"),
     *                 example={
     *                      "url": "http://localhost:9900"
     *                  }
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
    public function subscribeToTopic(SubscribeToTopicRequest $request) {
   
        //start transaction since multiple tables are updated
        DB::beginTransaction();

        //check if subscriber url exists
        $subscriberUrl = strtolower(trim($request->url));
        $subscriberObject = $this->subscriberRepository->findByName($subscriberUrl);
        if(is_null($subscriberObject)){
            //create subscriber if subscriber does not exist
            $subscriberObject = $this->subscriberRepository->create(['subscriber_url' => $subscriberUrl]);

            if(is_null($subscriberObject)) {
                DB::rollback();
                return ApiJsonResponserService::sendData(HttpStatusCode::OK, "Subscribing to topic was not successful.", []);
            }
        }

        

        if($this->subscriberRepository->getSubscriberWithTopicRelations(request()->topic_id, $subscriberObject->id)->topic->count() == 0) {
            //check if subscribing via sync was successful
            if(!$this->subscriberRepository->subscribeToTopic($subscriberObject, request()->topic_id)) {
                DB::rollback();
                return ApiJsonResponserService::sendData(HttpStatusCode::OK, "Subscribing to topic was not successful.", []);
            }
        }

        DB::commit();

        //Publish job to rabbit-mq to communicate with subscribers
        TopicSubscribed::dispatch(request()->topic_id, $request->url, now())->onQueue('subscribers');

        return ApiJsonResponserService::sendData(HttpStatusCode::OK, "Successfully subscribed to topic.", ["url" => $subscriberUrl, "topic" => ucfirst(trim(request()->topic_title))]);
        
    }



     /**
     * This function is used to list topics
     * @return Illuminate\HTTP\JsonResponse
     */
    /** 
     * @OA\Get(
     *     path="/api/topic/list",
     *     summary="List topics | route('/topic/list')",
     *     description="List topics ",
     *     tags={"Publisher app"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
    public function listTopic() {

        $listOfTopics = $this->topicsRepository->listTopic();

        if($listOfTopics->count() == 0) {
            return ApiJsonResponserService::sendData(HttpStatusCode::OK, "Not topics was found kindly create a topic.", []);
        }

        return ApiJsonResponserService::sendData(HttpStatusCode::OK, "Topic listed Successfully.", ["data" => $listOfTopics]);
    }


}
