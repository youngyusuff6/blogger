<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'owner_id'];

    //Define Relationship 
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

}
