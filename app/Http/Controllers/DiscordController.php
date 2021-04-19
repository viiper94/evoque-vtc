<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Woeler\DiscordPhp\Message\DiscordEmbedMessage;
use Woeler\DiscordPhp\Webhook\DiscordWebhook;


class DiscordController extends Controller{

    public function index(){
        $message = (new DiscordEmbedMessage())
            ->setColor(14992641)
            ->setTitle()
            ->setImage()
            ->setDescription();
        $webhook = new DiscordWebhook(env('DISCORD_PAINT_WEBHOOK_URL'));
        $webhook->send($message);
    }

}
