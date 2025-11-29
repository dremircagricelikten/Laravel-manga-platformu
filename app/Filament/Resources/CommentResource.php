<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationLabel = 'Comments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Comment Details')
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->label('User')
                            ->disabled(),

                        Forms\Components\TextInput::make('commentable_type')
                            ->label('Commented On')
                            ->disabled()
                            ->formatStateUsing(fn ($state) => class_basename($state)),

                        Forms\Components\TextInput::make('commentable.title')
                            ->label('Item Title')
                            ->disabled(),

                        Forms\Components\Textarea::make('content')
                            ->label('Comment')
                            ->rows(4)
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('gif_url')
                            ->label('GIF URL')
                            ->url()
                            ->disabled()
                            ->visible(fn ($record) => $record && $record->gif_url),
                    ])->columns(2),

                Forms\Components\Section::make('Moderation')
                    ->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approved')
                            ->helperText('Show/hide this comment'),

                        Forms\Components\Toggle::make('is_pinned')
                            ->label('Pinned')
                            ->helperText('Pin this comment to the top'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('commentable_type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->badge()
                    ->color(fn ($state) => match(class_basename($state)) {
                        'Series' => 'info',
                        'Chapter' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('commentable.title')
                    ->label('Commented On')
                    ->limit(30)
                    ->searchable()
                    ->url(fn ($record) => 
                        $record->commentable_type === 'App\Models\Series' 
                            ? "/series/{$record->commentable->slug}"
                            : "/chapter/{$record->commentable->slug}"
                    ),

                Tables\Columns\TextColumn::make('content')
                    ->label('Comment')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_pinned')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('commentable_type')
                    ->label('Type')
                    ->options([
                        'App\Models\Series' => 'Series',
                        'App\Models\Chapter' => 'Chapter',
                    ]),

                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Approved')
                    ->placeholder('All comments')
                    ->trueLabel('Approved only')
                    ->falseLabel('Pending only'),

                Tables\Filters\TernaryFilter::make('is_pinned')
                    ->label('Pinned'),

                Tables\Filters\Filter::make('has_parent')
                    ->label('Replies Only')
                    ->query(fn (Builder $query) => $query->whereNotNull('parent_id')),

                Tables\Filters\Filter::make('top_level')
                    ->label('Top-Level Only')
                    ->query(fn (Builder $query) => $query->whereNull('parent_id')),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Comment $record) => $record->update(['is_approved' => true]))
                    ->visible(fn (Comment $record) => !$record->is_approved),

                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (Comment $record) => $record->update(['is_approved' => false]))
                    ->visible(fn (Comment $record) => $record->is_approved),

                Tables\Actions\Action::make('pin')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->action(fn (Comment $record) => $record->update(['is_pinned' => !$record->is_pinned]))
                    ->label(fn (Comment $record) => $record->is_pinned ? 'Unpin' : 'Pin'),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true])),

                    Tables\Actions\BulkAction::make('reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_approved' => false])),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_approved', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
