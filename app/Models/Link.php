<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['url', 'type'];
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }    
}
