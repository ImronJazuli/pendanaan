<?php

namespace App\Providers;

use App\Models\Kausa;
use App\Policies\KausaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Kausa::class, KausaPolicy::class);

        View::composer('*', function ($view) {
            if (! array_key_exists('errors', $view->getData())) {
                $view->with('errors', session('errors') ?? new ViewErrorBag);
            }
        });
    }
}
