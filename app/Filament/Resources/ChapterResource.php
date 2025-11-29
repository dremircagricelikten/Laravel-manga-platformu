<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChapterResource\Pages;
use App\Models\Chapter;
use App\Models\Series;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ChapterResource extends Resource
{
    protected static ?string $model = Chapter::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\Select::make('series_id')
                        ->label('Series')
                        ->relationship('series', 'title')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (Forms\Set $set) => $set('volume_id', null)),

                    Forms\Components\Select::make('volume_id')
                        ->label('Volume (Optional)')
                        ->relationship(
                            'volume',
                            'title',
                            fn ($query, Forms\Get $get) => 
                                $query->where('series_id', $get('series_id'))
                        )
                        ->searchable()
                        ->preload(),

                    Forms\Components\TextInput::make('chapter_number')
                        ->required()
                        ->numeric()
                        ->step(0.1)
                        ->helperText('Can use decimals for special chapters (e.g., 5.5)'),

                    Forms\Components\TextInput::make('title')
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                            if ($state) {
                                $set('slug', Str::slug($state));
                            } else {
                                $chapterNum = $get('chapter_number');
                                $set('slug', 'chapter-' . $chapterNum);
                            }
                        }),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Auto-generated from title or chapter number'),
                ])->columns(2),

            Forms\Components\Section::make('Content')
                ->schema([
                    // For Manga - ZIP Upload
                    Forms\Components\FileUpload::make('chapter_zip')
                        ->label('Chapter ZIP File')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                        ->maxSize(512000) // 500MB
                        ->directory('chapters/zips')
                        ->helperText('Upload a ZIP file containing all manga pages (will be extracted automatically)')
                        ->visible(function (Forms\Get $get): bool {
                            $seriesId = $get('series_id');
                            if (!$seriesId) return false;
                            $series = Series::find($seriesId);
                            return $series?->type === 'manga';
                        })
                        ->columnSpanFull(),

                    // For Manga - Manual Images (Alternative)
                    Forms\Components\FileUpload::make('images')
                        ->label('Or Upload Images Separately')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->directory('chapters/images')
                        ->imageEditor()
                        ->maxFiles(200)
                        ->helperText('Alternative: Upload manga pages one by one in order')
                        ->visible(function (Forms\Get $get): bool {
                            $seriesId = $get('series_id');
                            if (!$seriesId) return false;
                            $series = Series::find($seriesId);
                            return $series?->type === 'manga';
                        })
                        ->columnSpanFull(),

                    // For Novels - Rich Text
                    Forms\Components\RichEditor::make('content')
                        ->label('Novel Content')
                        ->toolbarButtons([
                            'blockquote',
                            'bold',
                            'bulletList',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->visible(function (Forms\Get $get): bool {
                            $seriesId = $get('series_id');
                            if (!$seriesId) return false;
                            $series = Series::find($seriesId);
                            return $series?->type === 'novel';
                        })
                        ->columnSpanFull(),

                    // For Anime - Video Embed
                    Forms\Components\Textarea::make('video_embed')
                        ->label('Video Embed Code or URL')
                        ->rows(3)
                        ->helperText('Paste embed code or video URL')
                        ->visible(function (Forms\Get $get): bool {
                            $seriesId = $get('series_id');
                            if (!$seriesId) return false;
                            $series = Series::find($seriesId);
                            return $series?->type === 'anime';
                        })
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Access Control & Monetization')
                ->schema([
                    Forms\Components\Toggle::make('is_premium')
                        ->label('Premium Chapter')
                        ->helperText('Users need to unlock this chapter')
                        ->live()
                        ->default(false),

                    Forms\Components\TextInput::make('unlock_cost')
                        ->label('Unlock Cost (Ki Coins)')
                        ->numeric()
                        ->default(setting('default_chapter_cost', 10))
                        ->visible(fn (Forms\Get $get): bool => $get('is_premium'))
                        ->helperText('How many Ki coins required to unlock'),

                    Forms\Components\TextInput::make('lock_duration_days')
                        ->label('Lock Duration (Days)')
                        ->numeric()
                        ->default(setting('default_lock_duration', 3))
                        ->visible(fn (Forms\Get $get): bool => $get('is_premium'))
                        ->helperText('Chapter becomes free after this many days'),
                ])->columns(3),

            Forms\Components\Section::make('Publishing')
                ->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Published')
                        ->default(true),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Publish Date & Time')
                        ->default(now())
                        ->helperText('Users cannot see chapters scheduled for future'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('series.title')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('chapter_number')
                    ->label('Ch. #')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_premium')
                    ->boolean()
                    ->label('Premium')
                    ->sortable(),

                Tables\Columns\TextColumn::make('unlock_cost')
                    ->label('Cost (Ki)')
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('views')
                    ->label('Views')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('series')
                    ->relationship('series', 'title')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\TernaryFilter::make('is_premium')
                    ->label('Premium'),
                    
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChapters::route('/'),
            'create' => Pages\CreateChapter::route('/create'),
            'bulk-upload' => Pages\BulkUploadChapters::route('/bulk-upload'),
            'edit' => Pages\EditChapter::route('/{record}/edit'),
        ];
    }
}
