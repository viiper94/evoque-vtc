<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kb extends Model{

    protected $fillable = [
        'title',
        'category',
        'article',
    ];

    protected $table = 'kb';

    public function user(){
        return $this->hasOne('App\User', 'id', 'author');
    }

    public function setCommentAttribute($value){
        $this->attributes['article'] = str_replace(PHP_EOL, '  '.PHP_EOL, $value);
    }

}
