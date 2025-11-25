<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Services\WalletService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('User Information')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\FileUpload::make('avatar')
                        ->image()
                        ->directory('avatars')
                        ->imageEditor(),

                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create')
                        ->maxLength(255),
                ])->columns(2),

            Forms\Components\Section::make('VIP Status')
                ->schema([
                    Forms\Components\Toggle::make('is_vip')
                        ->label('VIP Member')
                        ->live(),

                    Forms\Components\DateTimePicker::make('vip_expires_at')
                        ->label('VIP Expiration Date')
                        ->visible(fn (Forms\Get $get): bool => $get('is_vip'))
                        ->helperText('Leave empty for permanent VIP'),
                ])->columns(2),

            Forms\Components\Section::make('Roles & Permissions')
                ->schema([
                    Forms\Components\CheckboxList::make('roles')
                        ->relationship('roles', 'name')
                        ->columns(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name)),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('wallet.balance')
                    ->label('Ki Balance')
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0) . ' ' . setting('coin_name', 'Ki'))
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_vip')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('vip_expires_at')
                    ->label('VIP Expires')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('Last Login')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_vip')
                    ->label('VIP Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('adjust_balance')
                    ->label('Adjust Balance')
                    ->icon('heroicon-o-currency-dollar')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->helperText('Use negative number to deduct coins'),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->helperText('Reason for adjustment'),
                    ])
                    ->action(function (User $record, array $data): void {
                        $walletService = app(WalletService::class);
                        
                        try {
                            $walletService->adjust(
                                user: $record,
                                amount: $data['amount'],
                                description: $data['description']
                            );

                            Notification::make()
                                ->title('Balance adjusted successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error adjusting balance')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
