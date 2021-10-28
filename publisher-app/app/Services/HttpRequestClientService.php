<?php

namespace App\Services;

use GuzzleHttp\Client;

class HttpRequestClientService {

    /**
     * This function builds a Guzzle http client
     * @return Client
     */
    public function buildClient():Client {
        return new Client(['timeout'  => 50.0]);
    }

    /**
     * This function helps send http post request to a url
     * @param Client $client
     * @param String $url
     */
    public function postData(Client $client, String $url, array $body ) {
        return $client->post($url, ["form_params" => $body]);
    }

}