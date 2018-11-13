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

    public function onUserCreated(\App\Events\UserCreated $event) {
        $user = $event->user;
        \Mail::send('emails.auth.confirm', compact('user'), function ($message) use ($user) {
           $message->to($user->email);
           $message->subject(
               sprintf('[%s] 회원 가입을 확인해 주세요.', config('app.name'))
           );
        });
    }
}
