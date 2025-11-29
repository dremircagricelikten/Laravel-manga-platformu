<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'E-Commerce';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Details')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->disabled(),
                        
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->disabled(),

                        Forms\Components\TextInput::make('final_amount')
                            ->prefix('₺')
                            ->disabled(),

                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'paytr' => 'PayTR',
                                'bank_transfer' => 'Bank Transfer',
                            ])
                            ->disabled(),

                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Bank Transfer')
                    ->schema([
                        Forms\Components\FileUpload::make('bank_receipt')
                            ->label('Receipt')
                            ->disk('public')
                            ->downloadable()
                            ->openable()
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('bank_transfer_date')
                            ->disabled(),
                    ])
                    ->visible(fn ($record) => $record && $record->payment_method === 'bank_transfer')
                    ->columns(2),

                Forms\Components\Section::make('Admin')
                    ->schema([
                        Forms\Components\Textarea::make('admin_notes')
                            ->rows(3),

                        Forms\Components\DateTimePicker::make('approved_at')
                            ->disabled(),

                        Forms\Components\Select::make('approved_by')
                            ->relationship('approvedBy', 'name')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('final_amount')
                    ->money('TRY')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('payment_method')
                    ->colors([
                        'info' => 'paytr',
                        'warning' => 'bank_transfer',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'paytr' ? 'PayTR' : 'Bank Transfer'),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'secondary' => 'cancelled',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'paytr' => 'PayTR',
                        'bank_transfer' => 'Bank Transfer',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\Filter::make('bank_transfer_pending')
                    ->label('Bank Transfer - Pending Approval')
                    ->query(fn (Builder $query) => 
                        $query->where('payment_method', 'bank_transfer')
                              ->where('payment_status', 'processing')
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update([
                            'payment_status' => 'paid',
                            'approved_at' => now(),
                            'approved_by' => auth()->id(),
                        ]);
                        $record->markAsPaid();
                    })
                    ->visible(fn (Order $record) => 
                        $record->payment_method === 'bank_transfer' && 
                        $record->payment_status === 'processing'
                    ),

                Tables\Actions\Action::make('cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Order $record) => $record->update(['payment_status' => 'cancelled']))
                    ->visible(fn (Order $record) => in_array($record->payment_status, ['pending', 'processing'])),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->payment_method === 'bank_transfer' && $record->payment_status === 'processing') {
                                    $record->update([
                                        'payment_status' => 'paid',
                                        'approved_at' => now(),
                                        'approved_by' => auth()->id(),
                                    ]);
                                    $record->markAsPaid();
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('payment_method', 'bank_transfer')
            ->where('payment_status', 'processing')
            ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
