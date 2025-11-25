<?php

namespace App\Filament\Resources\ChapterResource\Pages;

use App\Filament\Resources\ChapterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChapters extends ListRecords
{
    protected static string $resource = ChapterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('bulk_upload')
                ->label('Bulk Upload')
                ->icon('heroicon-o-arrow-up-tray')
                ->url(fn (): string => static::$resource::getUrl('bulk-upload'))
                ->color('success'),
            Actions\CreateAction::make(),
        ];
    }
}
