<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RpReport extends Model{

    protected $casts = [
        'images' => 'array',
    ];

    protected $fillable = [
        'note',
        'game',
        'comment'
    ];

    public function member(){
        return $this->belongsTo('App\Member');
    }

    public function deleteImages($folder){
        foreach($this->images as $image){
            $this->removeFile($folder.$image);
        }
    }

    private function removeFile($path){
        return is_file($path) ? unlink($path) : false;
    }

}
