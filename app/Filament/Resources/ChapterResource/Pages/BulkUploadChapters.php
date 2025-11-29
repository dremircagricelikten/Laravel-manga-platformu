<?php

namespace App\Filament\Resources\ChapterResource\Pages;

use App\Filament\Resources\ChapterResource;
use App\Models\Chapter;
use App\Models\Series;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class BulkUploadChapters extends Page
{
    protected static string $resource = ChapterResource::class;
    protected static string $view = 'filament.resources.chapter-resource.pages.bulk-upload';
    
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('series_id')
                    ->label('Series')
                    ->options(Series::pluck('title', 'id'))
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('volume_id', null)),

                Forms\Components\Select::make('volume_id')
                    ->label('Volume (Optional)')
                    ->options(fn (Forms\Get $get) => 
                        \App\Models\Volume::where('series_id', $get('series_id'))
                            ->pluck('title', 'id')
                    )
                    ->searchable(),

                Forms\Components\Repeater::make('chapters')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('chapter_number')
                                    ->required()
                                    ->numeric()
                                    ->step(0.1)
                                    ->default(function ($get) {
                                        $chapters = $get('../../chapters') ?? [];
                                        return count($chapters) + 1;
                                    }),

                                Forms\Components\TextInput::make('title')
                                    ->maxLength(255),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->default(now())
                                    ->seconds(false),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('is_premium')
                                    ->label('Premium')
                                    ->default(false),

                                Forms\Components\TextInput::make('unlock_cost')
                                    ->label('Cost (Ki)')
                                    ->numeric()
                                    ->default(setting('default_chapter_cost', 10))
                                    ->visible(fn (Forms\Get $get): bool => $get('is_premium')),

                                Forms\Components\TextInput::make('lock_duration_days')
                                    ->label('Lock Days')
                                    ->numeric()
                                    ->default(setting('default_lock_duration', 3))
                                    ->visible(fn (Forms\Get $get): bool => $get('is_premium')),
                            ]),

                        Forms\Components\FileUpload::make('chapter_zip')
                            ->label('Chapter ZIP File')
                            ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                            ->maxSize(512000)
                            ->directory('chapters/zips')
                            ->helperText('Upload ZIP file containing manga pages')
                            ->visible(function (Forms\Get $get): bool {
                                $seriesId = $get('../../series_id');
                                if (!$seriesId) return false;
                                $series = Series::find($seriesId);
                                return $series?->type === 'manga';
                            })
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('images')
                            ->label('Or Upload Images Separately')
                            ->multiple()
                            ->image()
                            ->directory('chapters/images')
                            ->reorderable()
                            ->helperText('Alternative: Upload images one by one')
                            ->visible(function (Forms\Get $get): bool {
                                $seriesId = $get('../../series_id');
                                if (!$seriesId) return false;
                                $series = Series::find($seriesId);
                                return $series?->type === 'manga';
                            })
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('content')
                            ->label('Novel Content')
                            ->visible(function (Forms\Get $get): bool {
                                $seriesId = $get('../../series_id');
                                if (!$seriesId) return false;
                                $series = Series::find($seriesId);
                                return $series?->type === 'novel';
                            })
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('video_embed')
                            ->label('Video Embed')
                            ->rows(2)
                            ->visible(function (Forms\Get $get): bool {
                                $seriesId = $get('../../series_id');
                                if (!$seriesId) return false;
                                $series = Series::find($seriesId);
                                return $series?->type === 'anime';
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->defaultItems(1)
                    ->addActionLabel('Add Chapter')
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => 
                        isset($state['chapter_number']) 
                            ? "Chapter {$state['chapter_number']}" . ($state['title'] ?? '')
                            : null
                    ),
            ])
            ->statePath('data');
    }

    public function upload(): void
    {
        $data = $this->form->getState();

        $successCount = 0;
        foreach ($data['chapters'] as $chapterData) {
            try {
                $slug = $chapterData['title'] 
                    ? Str::slug($chapterData['title']) 
                    : 'chapter-' . $chapterData['chapter_number'];

                Chapter::create([
                    'series_id' => $data['series_id'],
                    'volume_id' => $data['volume_id'] ?? null,
                    'chapter_number' => $chapterData['chapter_number'],
                    'title' => $chapterData['title'] ?? null,
                    'slug' => $slug,
                    'images' => $chapterData['images'] ?? [],
                    'content' => $chapterData['content'] ?? null,
                    'video_embed' => $chapterData['video_embed'] ?? null,
                    'is_premium' => $chapterData['is_premium'] ?? false,
                    'unlock_cost' => $chapterData['unlock_cost'] ?? 0,
                    'lock_duration_days' => $chapterData['lock_duration_days'] ?? 3,
                    'published_at' => $chapterData['published_at'] ?? now(),
                    'is_published' => true,
                ]);
                
                $successCount++;
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error uploading chapter ' . ($chapterData['chapter_number'] ?? ''))
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        }

        if ($successCount > 0) {
            Notification::make()
                ->title("{$successCount} chapters uploaded successfully!")
                ->success()
                ->send();

            $this->redirect(ChapterResource::getUrl('index'));
        }
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('upload')
                ->label('Upload All Chapters')
                ->action('upload')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray'),
        ];
    }
}
