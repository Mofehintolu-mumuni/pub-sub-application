<?php

namespace App\Models;

use App\Models\Message;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';

    public $timestamps = true;

    protected $fillable = ['title'];

    public function message() {
        return $this->hasMany(Message::class, 'topic_id', 'id');
    }

    public function subscriber() {
        return $this->belongsToMany(Subscriber::class, 'subscribers_topics', 'topic_id', 'subscriber_id')->withTimestamps();
    }
}
