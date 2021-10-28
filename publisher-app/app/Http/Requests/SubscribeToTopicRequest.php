<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use App\Repositories\TopicsRepository;
use Illuminate\Foundation\Http\FormRequest;

class SubscribeToTopicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function all($keys = null)
    {
    // Add route parameters to validation data
    return array_merge(parent::all(), $this->route()->parameters());
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'topicId' => 'integer|required|exists:topics,id',
            'url' => 'required|url',
        ];
    }

    public function messages()
    {
       return [
            'topicId.required' => 'Please provide a valid topic',
            'topicId.integer' => 'Please provide a valid topic',
            'url.required' => 'Please provide a valid url',
            'url.url' => 'The url provided must be a valid url',
       ];
    }

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function($validator) {
            $this->mergeTopicData();
        });
    }
    
    protected function mergeTopicData() {
        $topicRepository = \App::make(TopicsRepository::class);
        $topicObject = $topicRepository->findById(request()->topicId);
        $topicData = [
                        'topic_title' => null,
                        'topic_id' => null
                     ];

        if(!is_null($topicObject)) {
     
                $topicData['topic_title'] = $topicObject->title;
                $topicData['topic_id'] = $topicObject->id;
                $topicData['created_at'] = $topicObject->created_at;
        }

        request()->merge($topicData);
    }

}
