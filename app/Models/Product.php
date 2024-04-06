<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =[
        'article',
        'name',
        'status',
        'data'
    ];
    
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
   
}
