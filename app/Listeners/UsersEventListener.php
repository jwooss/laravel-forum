<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class UsersEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        //
    }

    public function subscribe(\Illuminate\Events\Dispatcher $events)
    {
        // 여러개 등록 가능하다..
        $events->listen(
            \App\Events\UserCreated::class,
            __CLASS__ . '@onUsersCreated'
        );

        $events->listen(
            \App\Events\PasswordRemindCreated::class,
            __CLASS__ . '@onPasswordRemindCreated'
        );
    }


    public function onUsersCreated(\App\Events\UserCreated $event)
    {
        $user = $event->user;
        \Mail::send('emails.auth.confirm', compact('user'), function ($message) use ($user) {
            $message->to($user->email);
            $message->subject(
                sprintf('[%s] 회원 가입을 확인해 주세요.', config('app.name'))
            );
        });
    }

    public function onPasswordRemindCreated(\App\Events\PasswordRemindCreated $event)
    {
        Mail::send('emails.passwords.reset',
            ['token' => $event->token],
            function ($message) use ($event) {
                $message->to($event->email);
                $message->subject(
                    sprintf('[%s] 비밀번호를 초기화하세요. ', config('app.name'))
                );
            });
    }
}
