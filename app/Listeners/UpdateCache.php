<?php

namespace App\Listeners;

use Illuminate\Cache\Events\KeyForgotten;

class UpdateCache
{
    public function handle(KeyForgotten $event){
        $key = $event->key;

        //TODO update cache based on key
    }
}
