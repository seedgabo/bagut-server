<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => ['api','auth.basic.once']]);

        /*
         * Authenticate the user's personal channel...
         */
        Broadcast::channel('App.Event', function ($user) {
            return ['id' => $user->id, 'name' => $user->nombre];
        });

        Broadcast::channel('App.User.*', function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        });


        Broadcast::channel('chat.*', function ($user, $roomId) {
            if ($user->admin == 1) {
                return ['id' => $user->id, 'nombre' => $user->nombre];
            }
        });
    }
}
