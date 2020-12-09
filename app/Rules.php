<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Rules extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    protected $table = 'rules';

    protected $fillable = [
        'paragraph',
        'title',
        'text',
    ];

    protected $casts = [
        'public' => 'boolean',
//        'history' => 'array'
    ];

    public function setTextAttribute($value){
        $this->attributes['text'] = str_replace(PHP_EOL, '  '.PHP_EOL, $value);
    }

}
