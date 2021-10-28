<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EndpointsTest extends TestCase
{ use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


         /**
     * Send money to user account.
     *
     * @return void
     */
    public function test_create_topic()
    {
        $response = $this->withHeaders([
                                        'Content-Type' => 'application/json',
                                        'Accpet' => 'application/json'
                                        ])->json('POST', '/api/create-topic', [
                                            "topic" => "Hello world"
                                        ]);
                                     
                                    
        $response->assertStatus(201);

    }

    public function test_list_topic()
    {
        $this->makeTopic();
    
        $response = $this->withHeaders([
                                        'Content-Type' => 'application/json',
                                        'Accpet' => 'application/json'
                                        ])->json('GET', '/api/topic/list');
                                     
                                    
        $response->assertStatus(200);
    }

    public function test_subscribe_to_topic()
    { 
        $topicId = json_decode($this->makeTopic()->getContent(), 1)['data']['id'];
        $response = $this->withHeaders([
                                        'Content-Type' => 'application/json',
                                        'Accpet' => 'application/json'
                                        ])->json('POST', "/api/subscribe/{$topicId}", [
                                            "url" => "http://localhost:9900"
                                        ]);
                                     
                                    
        $response->assertStatus(200);

    }

    public function test_publish_message_to_topic()
    {
        $topicId = json_decode($this->makeTopic()->getContent(), 1)['data']['id'];
        $response = $this->withHeaders([
                                        'Content-Type' => 'application/json',
                                        'Accpet' => 'application/json'
                                        ])->json('POST', "/api/publish/{$topicId}", [
                                            "message" => "This is a message for topic"
                                        ]);
                                     
                                    
        $response->assertStatus(200);

    }

    public function makeTopic() {
        $response = $this->withHeaders([
                                        'Content-Type' => 'application/json',
                                        'Accpet' => 'application/json'
                                        ])->json('POST', '/api/create-topic', [
                                            "topic" => "Hello world"
                                        ]);
        return $response;
    }

}
