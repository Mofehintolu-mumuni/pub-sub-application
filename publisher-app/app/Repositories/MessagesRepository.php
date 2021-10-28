<?php


namespace App\Repositories;

use App\Models\Message;
use App\Traits\ModelRepositoryTrait;
use App\Interfaces\ModelRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MessagesRepository implements ModelRepositoryInterface {
    use ModelRepositoryTrait;
    private $modelInstance;

    public function __construct(Message $message){
        $this->modelInstance = $message;
    }

    public function create(array $data): ?Object {
        return $this->modelInstance::create($data);
    }

}
