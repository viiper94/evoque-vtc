<?php

namespace App;

use App\Enums\Status;
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
    protected $casts = [
        'status' => 'integer',
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
        return Status::from($this->status)->name;
    }

}
