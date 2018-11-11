<?php
/**
 * Created by PhpStorm.
 * User: jwoos
 * Date: 12/11/2018
 * Time: 1:21 AM
 */

class UserEventListener
{
    public function subscribe(\Illuminate\Events\Dispatcher $events)
    {
        $events->listen(
            \App\Events\UserCreated::class,
            __CLASS__ . '@onUserCreated'
        );
    }

    public function onUserCreated(\App\Events\UserCreated $eveent) {

    }
}
