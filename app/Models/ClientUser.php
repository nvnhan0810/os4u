<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'username',
        'db',
        'domain',
    ];

    public function devices() {
        return $this->hasMany(ClientUserDevice::class, 'user_id', 'id');
    }
}
