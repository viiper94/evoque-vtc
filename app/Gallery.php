<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model{

    protected $table = 'gallery';

    protected $casts = [
        'visible' => 'boolean'
    ];

    public function user(){
        return $this->hasOne('App\User', 'id', 'uploaded_by');
    }

}
