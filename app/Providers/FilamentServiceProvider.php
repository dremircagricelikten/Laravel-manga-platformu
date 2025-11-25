<?php

namespace App\Providers;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        FilamentAsset::register([
            // Register custom assets here
        ]);

        FilamentIcon::register([
            // Register custom icons here
        ]);

        // Register render hook
        // FilamentView::registerRenderHook();
    }
}
