<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Tuning extends Model{

    public $table = 'official_tuning';

    protected $fillable = [
        'vendor',
        'model',
        'game',
        'type',
    ];

    public $casts = [
        'visible' => 'boolean'
    ];

    public function saveImage(UploadedFile $file, $path = '/images/tuning/'){
        $name = md5(time().$file->getClientOriginalName()).'.'. $file->getClientOriginalExtension();
        $file->move(public_path($path), $name);
        return $name;
    }

}
