<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 11;
    protected static ?string $navigationLabel = 'Menu';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Menu Item Details')
                ->schema([
                    Forms\Components\TextInput::make('label')
                        ->required()
                        ->maxLength(255)
                        ->label('Menu Label'),

                    Forms\Components\Select::make('type')
                        ->required()
                        ->options([
                            'page' => 'Page',
                            'url' => 'URL',
                            'route' => 'Route',
                        ])
                        ->default('page')
                        ->live()
                        ->afterStateUpdated(fn (Forms\Set $set) => $set('target', null)),

                    Forms\Components\Select::make('target')
                        ->label('Target')
                        ->required()
                        ->options(function (Forms\Get $get) {
                            $type = $get('type');
                            
                            if ($type === 'page') {
                                return Page::where('is_published', true)
                                    ->pluck('title', 'slug');
                            }
                            
                            if ($type === 'route') {
                                return [
                                    '/' => 'Home',
                                    '/browse' => 'Browse',
                                    '/latest' => 'Latest',
                                    '/popular' => 'Popular',
                                    '/coin-packages' => 'Coin Packages',
                                ];
                            }
                            
                            return [];
                        })
                        ->searchable()
                        ->visible(fn (Forms\Get $get) => in_array($get('type'), ['page', 'route'])),

                    Forms\Components\TextInput::make('target')
                        ->label('URL')
                        ->required()
                        ->url()
                        ->placeholder('https://example.com')
                        ->visible(fn (Forms\Get $get) => $get('type') === 'url'),

                    Forms\Components\Select::make('parent_id')
                        ->label('Parent Menu')
                        ->relationship('parent', 'label')
                        ->searchable()
                        ->placeholder('None (Top Level)'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Settings')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Show this menu item'),

                    Forms\Components\Toggle::make('open_in_new_tab')
                        ->label('Open in New Tab')
                        ->default(false)
                        ->helperText('For external links'),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower numbers appear first'),

                    Forms\Components\TextInput::make('icon')
                        ->label('Icon (Optional)')
                        ->maxLength(255)
                        ->placeholder('heroicon-o-home')
                        ->helperText('Heroicons icon name'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'page' => 'success',
                        'url' => 'warning',
                        'route' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('target')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('parent.label')
                    ->label('Parent')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->label('Active'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label('Order'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'page' => 'Page',
                        'url' => 'URL',
                        'route' => 'Route',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
