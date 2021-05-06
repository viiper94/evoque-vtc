<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Tab extends Model{

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date'
    ];

    protected $fillable = [
        'convoy_title',
        'lead_id',
        'comment',
    ];

    public $statuses = [
        '0' => 'Новый',
        '1' => 'Принят',
        '2' => 'Отклонён'
    ];

    public function member(){
        return $this->belongsTo('App\Member');
    }

    public function lead(){
        return $this->belongsTo('App\Member');
    }

    public function saveImage(UploadedFile $file, $path = '/images/convoys/tab/'){
        $name = md5(time().$file->getClientOriginalName()).'.'. $file->getClientOriginalExtension();
        $file->move(public_path($path), $name);
        return $name;
    }

    public function getStatus(){
        return $this->statuses[$this->status];
    }

}
