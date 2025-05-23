<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Convoy extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'title',
        'start_city',
        'start_company',
        'rest_city',
        'rest_company',
        'finish_city',
        'finish_company',
        'server',
        'communication',
        'communication_link',
        'communication_channel',
        'lead',
        'truck',
        'truck_tuning',
        'truck_paint',
        'trailer',
        'trailer_tuning',
        'trailer_paint',
        'cargo',
        'alt_trailer',
        'alt_trailer_tuning',
        'alt_trailer_paint',
        'alt_cargo',
        'comment',
        'links',
        'cargoman',
        'truck_with_tuning',
        'trailer_with_tuning',
        'public',
        'visible',
        'truck_public',
        'trailer_public',
    ];

    protected $casts = [
        'truck_public' => 'boolean',
        'trailer_public' => 'boolean',
        'public' => 'boolean',
        'visible' => 'boolean',
        'booking' => 'boolean',
        'route' => 'array',
        'links' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'start_time',
        'start_date',
    ];

    public $attributes_validation = [
        'title' => 'required|string',
        'start_date' => 'required|date',
        'start_time' => 'required|string',
        'server' => 'required|string',
        'links' => 'nullable|array',
        'cargoman' => 'nullable|string',

        'imageList' => 'required|string',
        'route' => 'nullable|array',
        'route.*' => 'image|max:7000',
        'start_city' => 'required|string',
        'start_company' => 'nullable|string',
        'rest_city' => 'nullable|string',
        'rest_company' => 'nullable|string',
        'finish_city' => 'required|string',
        'finish_company' => 'nullable|string',
        'dlc' => 'nullable|array',

        'communication' => 'required|string',
        'communication_link' => 'required|string',
        'communication_channel' => 'nullable|string',
        'lead' => 'nullable|string',

        'truck_image' => 'nullable|image|max:7000',
        'truck_with_tuning' => 'nullable|numeric',
        'truck' => 'nullable|string',
        'truck_tuning' => 'nullable|string',
        'truck_paint' => 'nullable|string',

        'trailer_image' => 'nullable|image|max:7000',
        'trailer_with_tuning' => 'nullable|numeric',
        'trailer' => 'nullable|string',
        'trailer_tuning' => 'nullable|string',
        'trailer_paint' => 'nullable|string',
        'cargo' => 'nullable|string',

        'alt_trailer_image' => 'nullable|image|max:7000',
        'alt_trailer' => 'nullable|string',
        'alt_trailer_tuning' => 'nullable|string',
        'alt_trailer_paint' => 'nullable|string',
        'alt_cargo' => 'nullable|string',
    ];

    public static $timesToType = [
        -1 => ['09:00', '09:15', '09:30', '09:45',
            '10:00', '10:15', '10:30', '10:45',
            '11:00', '11:15', '11:30', '11:45',
            '12:00', '12:15', '12:30', '12:45'],
        0 => ['13:00', '13:15', '13:30', '13:45',
            '14:00', '14:15', '14:30', '14:45',
            '15:00', '15:15', '15:30', '15:45',
            '16:00', '16:15', '16:30', '16:45'],
        1 => ['17:00', '17:15', '17:30', '17:45',
            '18:00', '18:15', '18:30', '18:45',
            '19:00', '19:15', '19:30', '19:45',
            '20:00', '20:15', '20:30', '20:45'],
        2 => ['21:00', '21:15', '21:30', '21:45',
            '22:00', '22:15', '22:30', '22:45',
            '23:00', '23:15', '23:30', '23:45'],
    ];

    public $defaultServers = [
        'Simulation 1' => 'ets2',
        'Simulation 2' => 'ets2',
        '[US] Simulation' => 'ets2',
        'Arcade' => 'ets2',
        'ProMods' => 'ets2',
        'Закрытая сeссия' => 'ets2',
        'Simulation' => 'ats',
        '[US] Simulatiоn' => 'ats',
        '[US] Arcade' => 'ats',
        'Закрытая сессия' => 'ats',
    ];

    protected $auditExclude = [
        'id',
        'start_date',
    ];

    public function isUpcoming(){
        return Carbon::now()->subMinutes(60)->lessThan($this->start_time);
    }

    public function getCommunicationLink(){
        $href = '';
        if($this->communication === 'TeamSpeak 3'){
            $href .= 'ts3server://';
        }
        return $href . $this->communication_link;
    }

    public function DLC(){
        return $this->belongsToMany('App\DLC', 'convoy_dlc', 'convoy_id', 'dlc_id');
    }

    public function bookedBy(){
        return $this->hasOne('App\Member', 'id', 'booked_by_id');
    }

    public function leadMember(){
        return $this->hasOne('App\Member', 'nickname', 'lead');
    }

    public function officialTruckTuning(){
        return $this->hasOne('App\Tuning', 'id', 'truck_with_tuning');
    }

    public function officialTrailerTuning(){
        return $this->hasOne('App\Tuning', 'id', 'trailer_with_tuning');
    }

    public function saveImage(UploadedFile $file, $path = '/images/convoys/', $suffix = null){
        $name = $this->start_time->format('Y-m-d')
            . '_'
            . $this->id
            . ($suffix ? '_'.$suffix.'_' : '_')
            . \Illuminate\Support\Str::random(5)
            . '.jpg';
        $img = Image::load($file->getPathName());
        if($img->getWidth() > 3000) $img->width(3000);
        $img->format(Manipulations::FORMAT_JPG)
            ->quality(85)
            ->save(public_path($path).$name);
        return $name;
    }

    public function syncImages($old, $new) :void{
        foreach(array_diff($old, $new) as $file_name){
            if(is_file(public_path('images/convoys/').$file_name)){
                unlink(public_path('images/convoys/').$file_name);
            }
        }
    }

    public function deleteImages($folder, $attr = ['route', 'truck_image', 'trailer_image', 'alt_trailer_image']){
        if($this->route && in_array('route', $attr)){
            foreach($this->route as $route){
                $this->removeFile($folder.$route);
            }
        }
        if(in_array('truck_image', $attr) && $this->truck_image) $this->removeFile($folder.$this->truck_image);
        if(in_array('trailer_image', $attr) && $this->trailer_image) $this->removeFile($folder.$this->trailer_image);
        if(in_array('alt_trailer_image', $attr) && $this->alt_trailer_image) $this->removeFile($folder.$this->alt_trailer_image);
    }

    private function removeFile($path){
        return is_file($path) && unlink($path);
    }

    public function getType(){
        return self::getTypeByNum($this->type);
    }

    public static function getTypeByNum($num): string{
        switch($num){
            case '0' : return 'дневной';
            case '2' : return 'ночной';
            case '-1' : return 'утренний';
            case '1' :
            default : return 'вечерний';
        }
    }

    public function setTypeByTime(): bool{
        $convoy_date = $this->start_time->format('Y-m-d');
        $morning_convoy_end = Carbon::parse($convoy_date . ' '.end(self::$timesToType[-1]));
        $day_convoy_start = Carbon::parse($convoy_date . ' '. self::$timesToType[0][0]);
        $day_convoy_end = Carbon::parse($convoy_date . ' '.end(self::$timesToType[0]));
        $evening_convoy_start = Carbon::parse($convoy_date . ' '. self::$timesToType[1][0]);
        $evening_convoy_end = Carbon::parse($convoy_date . ' '.end(self::$timesToType[1]));
        $night_convoy_start = Carbon::parse($convoy_date . ' '. self::$timesToType[2][0]);

        if($this->start_time->lessThanOrEqualTo($morning_convoy_end)){
            $this->type = -1;
        }elseif($this->start_time->lessThanOrEqualTo($day_convoy_end) && $this->start_time->greaterThanOrEqualTo($day_convoy_start)){
            $this->type = 0;
        }elseif($this->start_time->lessThanOrEqualTo($evening_convoy_end) && $this->start_time->greaterThanOrEqualTo($evening_convoy_start)){
            $this->type = 1;
        }elseif($this->start_time->greaterThanOrEqualTo($night_convoy_start)){
            $this->type = 2;
        }else{
            $this->type = 1;
        }
        return true;
    }

    public function isFulfilled(): bool{
        return (bool) $this->start_city;
    }

    public static function compressOldImages(){
        $convoys = Convoy::select('*')
            ->whereDate('start_date', '=', Carbon::today()->subMonths(3))
            ->get();
        // compressing route images
        foreach($convoys as $convoy){
            if($convoy->route){
                foreach($convoy->route as $image_name){
                    if(is_file(public_path('images/convoys/').$image_name)){
                        Image::load(public_path('images/convoys/').$image_name)
                            ->width(1280)
                            ->quality(70)
                            ->save(public_path('images/convoys/').$image_name);
                    }
                }
            }
            // compressing truck image
            if($convoy->truck_image && is_file(public_path('images/convoys/').$convoy->truck_image)){
                Image::load(public_path('images/convoys/').$convoy->truck_image)
                    ->width(1280)
                    ->quality(70)
                    ->save(public_path('images/convoys/').$convoy->truck_image);

            }
            // compressing trailer image
            if ($convoy->trailer_image && is_file(public_path('images/convoys/').$convoy->trailer_image)){
                Image::load(public_path('images/convoys/').$convoy->trailer_image)
                    ->width(1280)
                    ->quality(70)
                    ->save(public_path('images/convoys/').$convoy->trailer_image);

            }
            // compressing alt trailer image
            if ($convoy->alt_trailer_image && is_file(public_path('images/convoys/').$convoy->alt_trailer_image)){
                Image::load(public_path('images/convoys/').$convoy->alt_trailer_image)
                    ->width(1280)
                    ->quality(70)
                    ->save(public_path('images/convoys/').$convoy->alt_trailer_image);

            }
        }
    }

}
