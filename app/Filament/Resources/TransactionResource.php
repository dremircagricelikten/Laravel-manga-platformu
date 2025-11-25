<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Economy';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Read-only form for viewing
            Forms\Components\Section::make('Transaction Details')
                ->schema([
                    Forms\Components\TextInput::make('user.name')
                        ->label('User')
                        ->disabled(),

                    Forms\Components\Select::make('type')
                        ->options([
                            'purchase' => 'Purchase',
                            'spend' => 'Spend',
                            'refund' => 'Refund',
                            'admin_adjustment' => 'Admin Adjustment',
                        ])
                        ->disabled(),

                    Forms\Components\TextInput::make('amount')
                        ->disabled()
                        ->suffix(setting('coin_name', 'Ki')),

                    Forms\Components\TextInput::make('balance_after')
                        ->label('Balance After')
                        ->disabled()
                        ->suffix(setting('coin_name', 'Ki')),

                    Forms\Components\Textarea::make('description')
                        ->disabled()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('created_at')
                        ->label('Transaction Date')
                        ->disabled(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'purchase',
                        'danger' => 'spend',
                        'warning' => 'refund',
                        'primary' => 'admin_adjustment',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),

                Tables\Columns\TextColumn::make('amount')
                    ->money('USD', divideBy: 1)
                    ->suffix(' ' . setting('coin_name', 'Ki'))
                    ->sortable()
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('balance_after')
                    ->label('Balance After')
                    ->suffix(' ' . setting('coin_name', 'Ki'))
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'purchase' => 'Purchase',
                        'spend' => 'Spend',
                        'refund' => 'Refund',
                        'admin_adjustment' => 'Admin Adjustment',
                    ]),

                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Transactions are created by the system
    }

    public static function canEdit($record): bool
    {
        return false; // Transactions cannot be edited
    }

    public static function canDelete($record): bool
    {
        return false; // Transactions cannot be deleted
    }
}
