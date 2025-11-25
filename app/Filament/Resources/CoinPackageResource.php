<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoinPackageResource\Pages;
use App\Models\CoinPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CoinPackageResource extends Resource
{
    protected static ?string $model = CoinPackage::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Economy';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Package Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                            $set('slug', Str::slug($state))
                        ),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\Textarea::make('description')
                        ->maxLength(65535)
                        ->rows(3),
                ])->columns(2),

            Forms\Components\Section::make('Pricing')
                ->schema([
                    Forms\Components\TextInput::make('coin_amount')
                        ->label('Ki Coins Amount')
                        ->required()
                        ->numeric()
                        ->suffix(setting('coin_name', 'Ki')),

                    Forms\Components\TextInput::make('bonus_coins')
                        ->label('Bonus Ki Coins')
                        ->numeric()
                        ->default(0)
                        ->suffix(setting('coin_name', 'Ki'))
                        ->helperText('Extra coins given for free'),

                    Forms\Components\TextInput::make('price')
                        ->label('Price (in currency)')
                        ->required()
                        ->numeric()
                        ->prefix('$')
                        ->step(0.01),
                ])->columns(3),

            Forms\Components\Section::make('Settings')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Show this package to users'),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower numbers appear first'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('coin_amount')
                    ->label('Coins')
                    ->suffix(' ' . setting('coin_name', 'Ki'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('bonus_coins')
                    ->label('Bonus')
                    ->suffix(' ' . setting('coin_name', 'Ki'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_coins')
                    ->label('Total')
                    ->getStateUsing(fn ($record) => $record->coin_amount + $record->bonus_coins)
                    ->suffix(' ' . setting('coin_name', 'Ki'))
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
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
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoinPackages::route('/'),
            'create' => Pages\CreateCoinPackage::route('/create'),
            'edit' => Pages\EditCoinPackage::route('/{record}/edit'),
        ];
    }
}
