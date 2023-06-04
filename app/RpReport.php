<?php

namespace App;

use App\Enums\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Image;

class RpReport extends Model{

    protected $casts = [
        'images' => 'array',
        'status' => 'integer',
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
        return is_file($path) && unlink($path);
    }

    public function getStatus(){
        return Status::from($this->status)->name;
    }

    public static function compressOldImages(){
        $reports = RpReport::select(['images', 'id'])
            ->whereDate('created_at', '=', Carbon::today()->subMonth())
            ->get();
        // compressing images
        foreach($reports as $report){
            if($report->images){
                foreach($report->images as $image_name){
                    if(is_file(public_path('images/rp/').$image_name)){
                        Image::load(public_path('images/rp/').$image_name)
                            ->width(1280)
                            ->quality(70)
                            ->save(public_path('images/rp/').$image_name);
                    }
                }
            }
        }
    }

}
