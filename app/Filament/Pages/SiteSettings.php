<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class SiteSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getSettingsArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->icon('heroicon-o-building-storefront')
                            ->schema([
                                Forms\Components\TextInput::make('site_name')
                                    ->label('Site Name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\FileUpload::make('site_logo')
                                    ->label('Site Logo')
                                    ->image()
                                    ->directory('settings'),

                                Forms\Components\FileUpload::make('site_favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->directory('settings'),

                                Forms\Components\Textarea::make('site_description')
                                    ->label('Site Description')
                                    ->rows(3)
                                    ->maxLength(500),

                                Forms\Components\TextInput::make('contact_email')
                                    ->label('Contact Email')
                                    ->email(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Appearance')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Forms\Components\ColorPicker::make('primary_color')
                                    ->label('Primary Color')
                                    ->default('#3b82f6'),

                                Forms\Components\ColorPicker::make('secondary_color')
                                    ->label('Secondary Color')
                                    ->default('#8b5cf6'),

                                Forms\Components\Toggle::make('dark_mode_enabled')
                                    ->label('Enable Dark Mode')
                                    ->default(true),

                                Forms\Components\TextInput::make('items_per_page')
                                    ->label('Items Per Page')
                                    ->numeric()
                                    ->default(12),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Economy')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\TextInput::make('coin_name')
                                    ->label('Virtual Coin Name')
                                    ->default('Ki')
                                    ->required(),

                                Forms\Components\TextInput::make('default_chapter_cost')
                                    ->label('Default Chapter Unlock Cost')
                                    ->numeric()
                                    ->default(10)
                                    ->suffix('coins'),

                                Forms\Components\TextInput::make('default_lock_duration')
                                    ->label('Default Chapter Lock Duration')
                                    ->numeric()
                                    ->default(3)
                                    ->suffix('days'),

                                Forms\Components\TextInput::make('registration_bonus')
                                    ->label('Registration Bonus Coins')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Free coins given to new users'),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Social Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Forms\Components\TextInput::make('facebook_url')
                                    ->label('Facebook URL')
                                    ->url(),

                                Forms\Components\TextInput::make('twitter_url')
                                    ->label('Twitter/X URL')
                                    ->url(),

                                Forms\Components\TextInput::make('instagram_url')
                                    ->label('Instagram URL')
                                    ->url(),

                                Forms\Components\TextInput::make('discord_url')
                                    ->label('Discord URL')
                                    ->url(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(500),

                                Forms\Components\TagsInput::make('meta_keywords')
                                    ->label('Meta Keywords')
                                    ->separator(','),

                                Forms\Components\Textarea::make('google_analytics')
                                    ->label('Google Analytics Code')
                                    ->rows(4)
                                    ->helperText('Paste your GA4 tracking code'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getSettingsArray(): array
    {
        $settings = Setting::all();
        $data = [];
        
        foreach ($settings as $setting) {
            $data[$setting->key] = $setting->value;
        }

        return $data;
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getValueType($value),
                    'group' => $this->getGroupFromKey($key),
                ]
            );
        }

        // Clear settings cache
        Cache::forget('site_settings');

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    protected function getValueType($value): string
    {
        if (is_bool($value)) return 'boolean';
        if (is_int($value)) return 'integer';
        if (is_array($value)) return 'json';
        return 'string';
    }

    protected function getGroupFromKey(string $key): string
    {
        if (str_contains($key, 'coin') || str_contains($key, 'cost') || str_contains($key, 'lock')) {
            return 'economy';
        }
        if (str_contains($key, 'facebook') || str_contains($key, 'twitter') || str_contains($key, 'instagram') || str_contains($key, 'discord')) {
            return 'social';
        }
        if (str_contains($key, 'meta') || str_contains($key, 'seo') || str_contains($key, 'analytics')) {
            return 'seo';
        }
        if (str_contains($key, 'color') || str_contains($key, 'dark_mode') || str_contains($key, 'items')) {
            return 'appearance';
        }
        return 'general';
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save Settings')
                ->action('save')
                ->color('success'),
        ];
    }
}
