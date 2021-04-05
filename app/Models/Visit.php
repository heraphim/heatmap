<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'timestamp'];
    public $timestamps = false;
    public function link()
    {
        return $this->belongsTo(Link::class);
    }   
}
