<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeriesResource\Pages;
use App\Filament\Resources\SeriesResource\RelationManagers;
use App\Models\Series;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SeriesResource extends Resource
{
    protected static ?string $model = Series::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\Select::make('type')
                        ->options([
                            'manga' => 'Manga',
                            'novel' => 'Novel',
                            'anime' => 'Anime',
                        ])
                        ->required()
                        ->native(false)
                        ->live(),

                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                            $set('slug', Str::slug($state))
                        ),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('URL-friendly version of the title'),

                    Forms\Components\Select::make('status')
                        ->options([
                            'ongoing' => 'Ongoing',
                            'completed' => 'Completed',
                            'hiatus' => 'Hiatus',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->native(false)
                        ->default('ongoing'),
                ])->columns(2),

            Forms\Components\Section::make('Details')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->rows(4)
                        ->maxLength(65535)
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('cover_image')
                        ->image()
                        ->directory('series/covers')
                        ->imageEditor()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('author')
                        ->maxLength(255),
                        
                    Forms\Components\TextInput::make('artist')
                        ->maxLength(255),
                ])->columns(2),

            Forms\Components\Section::make('Categories')
                ->schema([
                    Forms\Components\Select::make('categories')
                        ->relationship('categories', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->createOptionForm([
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
                                ->unique(),
                            Forms\Components\Textarea::make('description')
                                ->maxLength(65535),
                        ])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->size(60)
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'manga',
                        'success' => 'novel',
                        'warning' => 'anime',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'completed',
                        'primary' => 'ongoing',
                        'warning' => 'hiatus',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('author')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('chapters_count')
                    ->counts('chapters')
                    ->label('Chapters')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'manga' => 'Manga',
                        'novel' => 'Novel',
                        'anime' => 'Anime',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'hiatus' => 'Hiatus',
                        'cancelled' => 'Cancelled',
                    ]),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChaptersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeries::route('/'),
            'create' => Pages\CreateSeries::route('/create'),
            'edit' => Pages\EditSeries::route('/{record}/edit'),
        ];
    }
}
