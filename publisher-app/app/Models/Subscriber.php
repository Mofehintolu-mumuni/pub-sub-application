<?php

namespace App\Models;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscriber extends Model
{
    use HasFactory;

    protected $table = 'subscribers';

    public $timestamps = true;

    protected $fillable = ['subscriber_url'];

    public function topic() {
        return $this->belongsToMany(Topic::class, 'subscribers_topics', 'subscriber_id', 'topic_id')->withTimestamps();
    }
}
