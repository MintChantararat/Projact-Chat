<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Database::class, function ($app) {
            return (new Factory)
                //->withServiceAccount(config('firebase.credentials')) // ใช้ Firebase Credentials
                ->withServiceAccount(config('FIREBASE_CREDENTIALS'))
                ->withDatabaseUri(config('FIREBASE_DATABASE_URL')) // ตั้งค่า Database URL
                ->createDatabase();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        View::composer('templates.header', function ($view) {
            $database = (new \Kreait\Firebase\Factory)
                ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
                ->createDatabase();

            $about = $database->getReference('about')->getValue();
            $view->with('headerAbout', $about);
        });
    }
}
