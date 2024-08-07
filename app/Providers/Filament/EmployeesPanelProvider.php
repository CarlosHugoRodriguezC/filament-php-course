<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class EmployeesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('staff')
            ->path('staff')
            ->login()
            ->default()
            ->profile()
            ->colors([
                'primary' => Color::Pink
            ])
            ->discoverResources(in: app_path('Filament/Employees/Resources'), for: 'App\\Filament\\Employees\\Resources')
            ->discoverPages(in: app_path('Filament/Employees/Pages'), for: 'App\\Filament\\Employees\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Employees/Widgets'), for: 'App\\Filament\\Employees\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])
            ->navigationItems([
                NavigationItem::make('Cahuroca')
                    ->url('https://carlos-rodriguez.pages.dev/')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-globe-alt')
                    ->sort(0),
            ])
            ->userMenuItems([
                UserMenuItem::make()
                    ->label('Admin panel')
                    ->url('/admin')
                    ->icon('heroicon-o-user-circle')
                    ->sort(0)
                    ->visible(fn(): bool => auth()->user()?->hasAnyRole(['super_admin']) ?? false),
            ]);
        ;
    }
}
