<?php

require 'kint.phar';

$dirname = 'truck_dealer';

// Open brand dealer folder
foreach(scandir($dirname) as $brand){
    if($brand != "." && $brand != ".."){

        // Open each truck file
        foreach(scandir($dirname.'/'.$brand) as $truck){
            if($truck != "." && $truck != ".."){

                $truck_content = file_get_contents($dirname.'/'.$brand.'/'.$truck);

                // Parsing truck file into accessories array
                preg_match_all('%\/def\/vehicle\/truck\/([\/a-zA-Z_0-9.]+)%', $truck_content, $matches);

                foreach($matches[1] as $accessory){

                    // Copy accessory def into final folder
                    $folders = explode('/', $accessory);
                    $path = 'vehicle/truck';
                    foreach($folders as $folder){
                        $path .= '/'. $folder;
                        if(stripos($folder, '.sii') === false){
                            if(!is_dir($path)) mkdir($path);
                        }else{
                            copy(str_replace('vehicle', 'original_vehicle', $path), $path);

                            // Replacing price abd unlock values
                            $accessory_content = file_get_contents($path);
                            $accessory_content = preg_replace('%price:\s[0-9]+%', 'price: 1', $accessory_content);
                            $accessory_content = preg_replace('%unlock:\s[0-9]+%', 'unlock: 1', $accessory_content);
                            file_put_contents($path, $accessory_content);
                        }
                    }

                }

            }
        }

    }
}
