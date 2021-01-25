<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
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
        'dlc',
        'comment',
    ];

    protected $casts = [
        'truck_public' => 'boolean',
        'trailer_public' => 'boolean',
        'public' => 'boolean',
        'visible' => 'boolean',
        'booking' => 'boolean',
        'dlc' => 'array',
        'route' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'start_time'
    ];

    public $attributes_validation = [
        'title' => 'required|string',
        'start_time' => 'required|date',
        'server' => 'required|string',

        'route' => 'nullable|array',
        'start_city' => 'required|string',
        'start_company' => 'nullable|string',
        'rest_city' => 'required|string',
        'rest_company' => 'nullable|string',
        'finish_city' => 'required|string',
        'finish_company' => 'nullable|string',
        'dlc' => 'nullable|array',

        'communication' => 'required|string',
        'communication_link' => 'required|string',
        'communication_channel' => 'nullable|string',
        'lead' => 'nullable|string',

        'truck_image' => 'nullable|image|max:3000',
        'truck' => 'nullable|string',
        'truck_tuning' => 'nullable|string',
        'truck_paint' => 'nullable|string',

        'trailer_image' => 'nullable|image|max:3000',
        'trailer' => 'nullable|string',
        'trailer_tuning' => 'nullable|string',
        'trailer_paint' => 'nullable|string',
        'cargo' => 'nullable|string',

        'alt_trailer_image' => 'nullable|image|max:3000',
        'alt_trailer' => 'nullable|string',
        'alt_trailer_tuning' => 'nullable|string',
        'alt_trailer_paint' => 'nullable|string',
        'alt_cargo' => 'nullable|string',
    ];

    public $dlcList = [
        'ets2' => [
            'ProMods',
            'DLC Going East!',
            'DLC Scandinavia',
            'DLC Vive la France!',
            'DLC Italia',
            'DLC Beyond The Baltic Sea',
            'DLC Road To The Black Sea',
//            'DLC Iberia',
            'DLC High Power Cargo Pack',
            'DLC Heavy Cargo Pack'
        ],
        'ats' => [
            'ProMods Canada',
            'DLC New Mexico',
            'DLC Oregon',
            'DLC Washington',
            'DLC Utah',
            'DLC Idaho',
            'DLC Colorado',
            'DLC Heavy Cargo Paсk',
            'DLC Forest Machinery'
        ]
    ];

    public function isUpcoming(){
        $now = Carbon::now();
        return $now->subMinutes(45)->lessThan($this->start_time);
    }

    public function getCommunicationLink(){
        $href = '';
        if($this->communication === 'TeamSpeak 3'){
            $href .= 'https://invite.teamspeak.com/';
        }
        return $href . $this->communication_link;
    }

    public function citiesList($game){
        $cities = [
            'ets2' => [
                'Абердин (Aberdeen)' => '',
                'Амстердам (Amsterdam)' => '',
                'Берлин (Berlin)' => '',
                'Берн (Bern)' => '',
                'Бирмингем (Birmingham)' => '',
                'Братислава (Bratislava)' => '',
                'Бремен (Bremen)' => '',
                'Брно (Brno)' => '',
                'Брюссель (Brussel)' => '',
                'Вена (Wien)' => '',
                'Венеция (Venezia)' => '',
                'Верона (Verona)' => '',
                'Вроцлав (Wrocław)' => '',
                'Гамбург (Hamburg)' => '',
                'Ганновер (Hannover)' => '',
                'Гласго (Glasgow)' => '',
                'Грац (Graz)' => '',
                'Гримсби (Grimsby)' => '',
                'Гронинген (Groningen)' => '',
                'Дижон (Dijon)' => '',
                'Дортмунд (Dortmund)' => '',
                'Дрезден (Dresden)' => '',
                'Дувр (Dover)' => '',
                'Дуйсбург (Duisburg)' => '',
                'Дюсельдорф (Dusseldorf)' => '',
                'Европорт (Europort)' => '',
                'Женева (Geneve)' => '',
                'Зальцбург (Salsburg)' => '',
                'Инсбрук (Innsbruck)' => '',
                'Кале (Calais)' => '',
                'Кардифф (Cardiff)' => '',
                'Карлайл (Carlisle)' => '',
                'Кассель (Kassel)' => '',
                'Кембридж (Сambridge)' => '',
                'Киль (Kiel)' => '',
                'Клагенфурт-ам-Вёртерзе (Klagenfurt am Wörthersee)' => '',
                'Кёльн (Köln)' => '',
                'Лейпциг (Leipzig)' => '',
                'Ливерпуль (Liverpool)' => '',
                'Лилль (Lille)' => '',
                'Линц (Linz)' => '',
                'Лион (Lyon)' => '',
                'Лондон (London)' => '',
                'Льеж (Liège)' => '',
                'Люксембург (Luxembourg)' => '',
                'Магдебург (Magdeburg)' => '',
                'Мангейм (Mannheim)' => '',
                'Манчестер (Manchester)' => '',
                'Мец (Metz)' => '',
                'Милан (Milano)' => '',
                'Мюнхен (München)' => '',
                'Ньюкасл-апон-Тайн (Newcastle upon Tyne)' => '',
                'Нюрнберг (Nürnberg)' => '',
                'Оснабрюк (Osnabrück)' => '',
                'Париж (Paris)' => '',
                'Плимут (Plymouth)' => '',
                'Познань (Poznań)' => '',
                'Прага (Praha)' => '',
                'Реймс (Reims)' => '',
                'Росток (Rostock)' => '',
                'Роттердам (Rotterdam)' => '',
                'Саутгемптон (Southampton)' => '',
                'Страсбург (Strassbourg)' => '',
                'Суонси (Swansea)' => '',
                'Травемюнде (Travemünde)' => '',
                'Турин (Torino)' => '',
                'Филикстоу (Felixstow)' => '',
                'Франкфурт-на-майне (Frankfurt am Main)' => '',
                'Халл (Hull)' => '',
                'Харидж (Harwich)' => '',
                'Цюрих (Zürich)' => '',
                'Шеффилд (Sheffield)' => '',
                'Штутгарт (Stuttgart)' => '',
                'Щецин (Szczecin)' => '',
                'Эдинбург (Edinburgh)' => '',
                'Эймёйден (IJmuiden)' => '',
                'Эрфурт (Erfurt)' => '',

                'Банска-Бистрица (Banská Bystrica)' => 'Going East!',
                'Белосток (Białystok)' => 'Going East!',
                'Будапешт (Budapest)' => 'Going East!',
                'Варшава (Warszawa)' => 'Going East!',
                'Гданьск (Gdańsk)' => 'Going East!',
                'Дебрецен (Debrecen)' => 'Going East!',
                'Катовице (Katowice)' => 'Going East!',
                'Кошице (Košice)' => 'Going East!',
                'Краков (Kraków)' => 'Going East!',
                'Лодзь (Łódź)' => 'Going East!',
                'Люблин (Lublin)' => 'Going East!',
                'Ольштын (Olsztyn)' => 'Going East!',
                'Острава (Ostrava)' => 'Going East!',
                'Печ (Pécs)' => 'Going East!',
                'Сегед (Szeged)' => 'Going East!',

                'Берген (Bergen)' => 'Scandinavia',
                'Векшё (Växjö)' => 'Scandinavia',
                'Вестерос (Västreås)' => 'Scandinavia',
                'Гдыня (Gdynia)' => 'Scandinavia',
                'Гедсер (Gedser)' => 'Scandinavia',
                'Гётеборг (Göteborg)' => 'Scandinavia',
                'Йёнчёпинг (Jönköping)' => 'Scandinavia',
                'Кальмар (Kalmar)' => 'Scandinavia',
                'Капельшер (Kapellskär)' => 'Scandinavia',
                'Карлскруна (Karlskrona)' => 'Scandinavia',
                'Копенгаген (København)' => 'Scandinavia',
                'Кристиансанн (Kristiansand)' => 'Scandinavia',
                'Линчёпинг (Linköping)' => 'Scandinavia',
                'Мальмё (Malmö)' => 'Scandinavia',
                'Нюнесхамн (Nynäshamn)' => 'Scandinavia',
                'Оденсе (Odense)' => 'Scandinavia',
                'Ольборг (Aalborg)' => 'Scandinavia',
                'Осло (Oslo)' => 'Scandinavia',
                'Ставангер (Stavanger)' => 'Scandinavia',
                'Стокгольм (Stockholm)' => 'Scandinavia',
                'Сёдертелье (Södertälje)' => 'Scandinavia',
                'Треллеборг (Trelleborg)' => 'Scandinavia',
                'Уппсала (Uppsala)' => 'Scandinavia',
                'Фредериксхавн (Frederikshavn)' => 'Scandinavia',
                'Хельсинблог (Helsingborg)' => 'Scandinavia',
                'Хиртсхальс (Hirtshals)' => 'Scandinavia',
                'Эребру (Örebro)' => 'Scandinavia',
                'Эсбьерг (Esbjerg)' => 'Scandinavia',

                'Аяччо (Ajaccio)' => 'Vive la France!',
                'Бастия (Bastia)' => 'Vive la France!',
                'Бонифачо (Bonifacio)' => 'Vive la France!',
                'Бордо (Bordeaux)' => 'Vive la France!',
                'Брест (Brest)' => 'Vive la France!',
                'Бурже (Le Bourget)' => 'Vive la France!',
                'Гавр (Le Havre)' => 'Vive la France!',
                'Гольфеш (Golfech)' => 'Vive la France!',
                'Кальви (Calvi)' => 'Vive la France!',
                'Клермон-Ферран (Clermont-Ferrand)' => 'Vive la France!',
                'Л\'Иль-Рус (L\'Île-Rousse)' => 'Vive la France!',
                'Ла-Рошель (La Rochelle)' => 'Vive la France!',
                'Ле-Ман (Le Mans)' => 'Vive la France!',
                'Лимож (Limoges)' => 'Vive la France!',
                'Марсель (Marseille)' => 'Vive la France!',
                'Монпелье (Montpellier)' => 'Vive la France!',
                'Нант (Nantes)' => 'Vive la France!',
                'Ницца (Nice)' => 'Vive la France!',
                'Палюэль (Paluel)' => 'Vive la France!',
                'Порто-Веккьо (Porto-Vecchio)' => 'Vive la France!',
                'Ренн (Rennes)' => 'Vive la France!',
                'Роскоф (Roscoff)' => 'Vive la France!',
                'Сен-Лоран (Saint-Laurent)' => 'Vive la France!',
                'Сент-Альбан-дю-Рон (Saint-Alban-du-Rhône)' => 'Vive la France!',
                'Сиво (Civaux)' => 'Vive la France!',
                'Тулуза (Toulouse)' => 'Vive la France!',

                'Анкона (Ancona)' => 'Italia',
                'Бари (Bari)' => 'Italia',
                'Болонья (Bologna)' => 'Italia',
                'Вилла-Сан-Джованни (Villa San Giovanni)' => 'Italia',
                'Генуя (Genova)' => 'Italia',
                'Кальяри (Cagliari)' => 'Italia',
                'Кассино (Cassino)' => 'Italia',
                'Катандзаро (Catanzaro)' => 'Italia',
                'Катания (Catania)' => 'Italia',
                'Ливорно (Livorno)' => 'Italia',
                'Мессина (Messina)' => 'Italia',
                'Неаполь (Napoli)' => 'Italia',
                'Ольбия (Olbia)' => 'Italia',
                'Палермо (Palermo)' => 'Italia',
                'Парма (Parma)' => 'Italia',
                'Пескара (Pescara)' => 'Italia',
                'Рим (Roma)' => 'Italia',
                'Сассари (Sassari)' => 'Italia',
                'Судзара (Suzzara)' => 'Italia',
                'Таранто (Taranto)' => 'Italia',
                'Терни (Terni)' => 'Italia',
                'Флоренция (Firenze)' => 'Italia',

                'Валмиера (Valmiera)' => 'Beyond The Baltic Sea',
                'Вентспилс (Ventspils)' => 'Beyond The Baltic Sea',
                'Вильнюс (Vilnius)' => 'Beyond The Baltic Sea',
                'Выборг' => 'Beyond The Baltic Sea',
                'Даугавпилс (Daugavpils)' => 'Beyond The Baltic Sea',
                'Калининград' => 'Beyond The Baltic Sea',
                'Каунас (Kaunas)' => 'Beyond The Baltic Sea',
                'Клайпеда (Klaipėda)' => 'Beyond The Baltic Sea',
                'Котка (Kotka)' => 'Beyond The Baltic Sea',
                'Коувола (Kouvola)' => 'Beyond The Baltic Sea',
                'Кунда (Kunda)' => 'Beyond The Baltic Sea',
                'Лахти (Lahti)' => 'Beyond The Baltic Sea',
                'Лиепая (Liepāja)' => 'Beyond The Baltic Sea',
                'Ловийса (Loviisa)' => 'Beyond The Baltic Sea',
                'Луга' => 'Beyond The Baltic Sea',
                'Мажейкяй (Mažeikiai)' => 'Beyond The Baltic Sea',
                'Наантали (Naantali)' => 'Beyond The Baltic Sea',
                'Нарва (Narva)' => 'Beyond The Baltic Sea',
                'Олкилуото (Olkiluoto)' => 'Beyond The Baltic Sea',
                'Палдиски (Paldiski)' => 'Beyond The Baltic Sea',
                'Паневежис (Panevėžys)' => 'Beyond The Baltic Sea',
                'Пори (Pori)' => 'Beyond The Baltic Sea',
                'Псков' => 'Beyond The Baltic Sea',
                'Пярну (Pärnu)' => 'Beyond The Baltic Sea',
                'Резекне (Rēzekne)' => 'Beyond The Baltic Sea',
                'Рига (Rīga)' => 'Beyond The Baltic Sea',
                'Санкт-Петербург' => 'Beyond The Baltic Sea',
                'Сосновый бор' => 'Beyond The Baltic Sea',
                'Таллин (Tallinn)' => 'Beyond The Baltic Sea',
                'Тампере (Tampere)' => 'Beyond The Baltic Sea',
                'Тарту (Tartu)' => 'Beyond The Baltic Sea',
                'Турку (Turku)' => 'Beyond The Baltic Sea',
                'Утена (Utena)' => 'Beyond The Baltic Sea',
                'Хельсинки (Helsinki)' => 'Beyond The Baltic Sea',
                'Шяуляй (Šiauliai)' => 'Beyond The Baltic Sea',

                'Бакэу (Bacău)' => 'Road To The Black Sea',
                'Брашов (Brașov)' => 'Road To The Black Sea',
                'Бургас' => 'Road To The Black Sea',
                'Бухарест (București)' => 'Road To The Black Sea',
                'Варна' => 'Road To The Black Sea',
                'Велико Търново' => 'Road To The Black Sea',
                'Галац (Galați)' => 'Road To The Black Sea',
                'Карлово' => 'Road To The Black Sea',
                'Клуж-Напока (Cluj-Napoca)' => 'Road To The Black Sea',
                'Козлодуй' => 'Road To The Black Sea',
                'Констанца (Constanța)' => 'Road To The Black Sea',
                'Крайова (Craiova)' => 'Road To The Black Sea',
                'Кэлэраши (Călărași)' => 'Road To The Black Sea',
                'Мангалия (Mangalia)' => 'Road To The Black Sea',
                'Перник' => 'Road To The Black Sea',
                'Пирдоп' => 'Road To The Black Sea',
                'Питешти (Pitești)' => 'Road To The Black Sea',
                'Плевен' => 'Road To The Black Sea',
                'Пловдив' => 'Road To The Black Sea',
                'Решица (Reșița)' => 'Road To The Black Sea',
                'Русе' => 'Road To The Black Sea',
                'София' => 'Road To The Black Sea',
                'Стамбул (İstanbul)' => 'Road To The Black Sea',
                'Текирдаг (Tekirdağ)' => 'Road To The Black Sea',
                'Тимишоара (Timișoara)' => 'Road To The Black Sea',
                'Тыргу-Муреш (Târgu Mureș)' => 'Road To The Black Sea',
                'Хунедоара (Hunedoara)' => 'Road To The Black Sea',
                'Чернаводэ (Cernavodă)' => 'Road To The Black Sea',
                'Эдирне (Edirne)' => 'Road To The Black Sea',
                'Яссы (Iași)' => 'Road To The Black Sea',
            ],
            'ats' => [

            ]
        ];
    }

    public function bookedBy(){
        return $this->hasOne('App\Member', 'id', 'booked_by_id');
    }

    public function leadMember(){
        return $this->hasOne('App\Member', 'nickname', 'lead');
    }

    public function saveImage(UploadedFile $file, $path = '/images/convoys/', $key = null){
        $name = substr(md5(time().$file->getClientOriginalName().$key), 0, 5).'.'. $file->getClientOriginalExtension();
        $file->move(public_path($path), $name);
        return $name;
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
        return is_file($path) ? unlink($path) : false;
    }

    public function getType(){
        return self::getTypeByNum($this->type);
    }

    public static function getTypeByNum($num): string{
        switch($num){
            case '0' : return 'дневной';
            case '2' : return 'ночной';
            case '3' : return 'утренний';
            case '1' :
            default : return 'вечерний';
        }
    }

    public function setTypeByTime(): bool{
        $convoy_date = $this->start_time->format('Y-m-d');
        $morning_convoy_end = Carbon::parse($convoy_date . ' 11:00');
        $day_convoy_start = Carbon::parse($convoy_date . ' 11:30');
        $day_convoy_end = Carbon::parse($convoy_date . ' 17:00');
        $evening_convoy_start = Carbon::parse($convoy_date . ' 17:30');
        $evening_convoy_end = Carbon::parse($convoy_date . ' 20:30');
        $night_convoy_start = Carbon::parse($convoy_date . ' 21:00');

        if($this->start_time->lessThanOrEqualTo($morning_convoy_end)){
            $this->type = 3;
        }elseif($this->start_time->lessThanOrEqualTo($day_convoy_end) && $this->start_time->greaterThan($day_convoy_start)){
            $this->type = 0;
        }elseif($this->start_time->lessThanOrEqualTo($evening_convoy_end) && $this->start_time->greaterThan($evening_convoy_start)){
            $this->type = 1;
        }elseif($this->start_time->greaterThan($night_convoy_start)){
            $this->type = 2;
        }else{
            $this->type = 1;
        }
        return true;
    }

    public function isFulfilled(): bool{
        return $this->start_city ? true : false;
    }

}
