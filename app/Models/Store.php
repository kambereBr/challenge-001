<?php
namespace App\Models;

use Core\Model;

class Store extends Model
{
    protected static $table = 'stores';

    public function users(): array
    {
        return User::all(['store_id' => $this->id]);
    }
}