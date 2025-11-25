<?php

namespace App\Filament\Resources\SeriesResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ChaptersRelationManager extends RelationManager
{
    protected static string $relationship = 'chapters';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('chapter_number')
                ->required()
                ->numeric()
                ->step(0.1),
                
            Forms\Components\TextInput::make('title')
                ->maxLength(255),
                
            Forms\Components\Toggle::make('is_published')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('chapter_number')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->limit(30),
                    
                Tables\Columns\IconColumn::make('is_premium')
                    ->boolean(),
                    
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('views_count')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            ->defaultSort('chapter_number', 'desc');
    }
}
