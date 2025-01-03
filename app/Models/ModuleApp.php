<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleApp extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'display_name',
    ];

    public function features() {
        return $this->hasMany(ModuleAppFeature::class, 'app_id', 'id');
    }
}
