<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Kb extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'title',
        'category',
        'article',
    ];

    protected $auditExclude = [
        'id',
        'author',
    ];

    protected $table = 'kb';

    protected $casts = [
        'visible' => 'boolean',
        'public' => 'boolean',
    ];

    public function user(){
        return $this->hasOne('App\User', 'id', 'author');
    }

}
