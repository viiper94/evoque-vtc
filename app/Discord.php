<?php

namespace App;


class Discord{

    protected static $webhooks = [
        '#баги' => 'https://discord.com/api/webhooks/797028152034852895/Mq_MW-pNLkHVblvfDt0uVQKRulRHDSucRT2vU5yIigb26BtMqEb9VJhJ8h3RHVzKfExl',
        '#открытый-конвой' => 'https://discord.com/api/webhooks/727068749601046530/2CJ_k6ML8Iflt2i_YRv_RbYNFKdgwcY1IjRZcvc-kC5KWJiQiVjkwm3iHtdFD7AYC5Bb',
    ];

    public static function sendErrorReport($errorCode){
        $curl = curl_init(self::$webhooks['#баги']);
        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'embeds' => [
                    [
                        'title' => 'Кто-то словил ошибку на сайте!',
                        'type' => 'rich',
                        'color' => 16711680,
                        'fields' => [
                            [
                                'name' => 'Код ошибки',
                                'value' => $errorCode,
                            ]
                        ]
                    ]
                ]
            ])
        ]);
        return curl_exec($curl);
    }

}
