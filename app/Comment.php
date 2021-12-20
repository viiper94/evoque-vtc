<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model{

    use HasFactory;

    protected $fillable = [
        'instance',
        'instance_id',
        'author_id',
        'text',
        'public',
    ];

    protected $casts = [
        'public' => 'boolean'
    ];

    public function author(){
        return $this->belongsTo('App\User', 'author_id');
    }

}
