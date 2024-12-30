<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientUserDevice extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id', 'device_id', 'device_info', 'status'
    ];
}