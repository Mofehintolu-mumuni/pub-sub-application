<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTopicRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
               'topic' => 'required|string'
        ];
    }

    public function messages()
    {
       return [
            'topic.required' => 'Please provide a topic to create',
            'topic.string' => 'The topic data type must be a string',
       ];
    }
}
