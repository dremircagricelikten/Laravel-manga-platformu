<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentSettingResource\Pages;
use App\Models\PaymentSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentSettingResource extends Resource
{
    protected static ?string $model = PaymentSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'E-Commerce';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Payment Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->disabled(),

                Forms\Components\Textarea::make('value')
                    ->rows(2)
                    ->helperText(fn ($record) => self::getKeyHelperText($record?->key)),

                Forms\Components\Select::make('group')
                    ->options([
                        'paytr' => 'PayTR',
                        'bank_transfer' => 'Bank Transfer',
                        'general' => 'General',
                    ])
                    ->disabled(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active'),
            ]);
    }

    private static function getKeyHelperText(?string $key): string
    {
        return match($key) {
            'paytr_merchant_id' => 'Your PayTR Merchant ID',
            'paytr_merchant_key' => 'Your PayTR Merchant Key',
            'paytr_merchant_salt' => 'Your PayTR Merchant Salt',
            'paytr_test_mode' => '1 for test mode, 0 for production',
            'bank_name' => 'Name of the bank',
            'bank_account_holder' => 'Account holder name',
            'bank_iban' => 'IBAN number (TR...)',
            'bank_branch' => 'Branch name or code',
            'bank_account_number' => 'Account number',
            default => '',
        };
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('group')
                    ->colors([
                        'primary' => 'paytr',
                        'warning' => 'bank_transfer',
                        'secondary' => 'general',
                    ]),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'paytr' => 'PayTR',
                        'bank_transfer' => 'Bank Transfer',
                        'general' => 'General',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentSettings::route('/'),
            'edit' => Pages\EditPaymentSetting::route('/{record}/edit'),
        ];
    }
}
