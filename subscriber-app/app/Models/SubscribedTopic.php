<?php

namespace App\Models;

use App\Models\Message;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscribedTopic extends Model
{
    use HasFactory;

    protected $table = 'subscribed_topic';

    public $timestamps = true;

    protected $fillable = ['topic_id', 'date_subscribed'];
}
