<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait ModelRepositoryTrait
{

    public function findById(int $resourceId): Object|null{
        return $this->modelInstance::find($resourceId);
    }

    public function create(array $data): Object|null {
        return $this->modelInstance::firstOrCreate($data);
    }

    public function delete(int $resourceId): bool{
        return $this->modelInstance::where('id', $resourceId)->delete();
    }

    public function update(int $resourceId, array $data): bool {
        return $this->modelInstance::where('id', $resourceId)->update($data);
    }

    public function getAll() : Collection {
        return $this->modelInstance::all();
    }

    public function getById(array $arrayOfIds) : Collection {
        return $this->modelInstance::whereIn('id', $arrayOfIds)->get();
    }
}