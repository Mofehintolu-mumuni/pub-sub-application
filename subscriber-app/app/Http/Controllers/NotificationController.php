<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\MessagePublishedForTopic;

class NotificationController extends Controller
{
    /**
     * This function initiates the event that enables data obtained from Request object to be broadcast via websocket clients
     * @param Request $request
     * @returns void
     */
    public function notify(Request $request):void {
        broadcast(new MessagePublishedForTopic($request->topic, json_decode($request->data)->message));
        Log::info([$request->topic, json_decode($request->data)->message]);
    }
}
