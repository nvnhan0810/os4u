<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleAppFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_id', 'name', 'display_name',
    ];

    public function moduleApp() {
        return $this->belongsTo(ModuleApp::class ,'app_id', 'id');
    }
}
