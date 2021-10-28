<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    public $timestamps = true;

    protected $fillable = ['message', 'topic_id'];

    public function message() {
        return $this->hasMany(Message::class, 'topic_id', 'id');
    }
}
