<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $notfoundlabel = "Sorry no record found.";
        View::share('notfoundlabel', $notfoundlabel);


         /// Global Folder Name in View file
        Config::set('DISK_NAME', 'public');
        Config::set('PROFILE_PIC_PATH', 'profile');
        Config::set('MEMBER_PIC_PATH', 'member');
        Config::set('GENERAL_IMG_PATH', 'general'); 
        Config::set('EVENT_PIC_PATH', 'event'); 
        View::share('DISK_NAME', 'public');
        View::share('PROFILE_PIC_PATH', 'profile');
        View::share('MEMBER_PIC_PATH', 'member');
        View::share('GENERAL_IMG_PATH', 'general'); 
        View::share('EVENT_PIC_PATH', 'event'); 
    }
}
