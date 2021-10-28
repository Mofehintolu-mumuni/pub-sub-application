<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\HttpStatusCode;
use App\Repositories\SubscriberRepository;
use App\Services\ApiJsonResponserService;

class SubscribersController extends Controller
{
    private $subscriberRepository;

    public function __construct(SubscriberRepository $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }
}
