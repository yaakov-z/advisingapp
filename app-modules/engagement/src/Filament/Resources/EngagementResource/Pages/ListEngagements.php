<?php

namespace Assist\Engagement\Filament\Resources\EngagementResource\Pages;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Assist\Engagement\Models\Engagement;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use Assist\Engagement\Filament\Resources\EngagementResource;
use Filament\Tables\Actions\CreateAction as TableCreateAction;

class ListEngagements extends ListRecords
{
    protected static string $resource = EngagementResource::class;

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->columns([
                TextColumn::make('user.name')
                    ->label('Created By'),
                TextColumn::make('subject'),
                TextColumn::make('body'),
                TextColumn::make('recipient.full')
                    ->label('Recipient'),
            ])
            ->filters([
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make()
                    ->hidden(fn (Engagement $record) => $record->hasBeenDelivered() === true),
                DeleteAction::make()
                    ->hidden(fn (Engagement $record) => $record->hasBeenDelivered() === true),
            ])
            ->bulkActions([
            ])
            ->emptyStateActions([
                TableCreateAction::make(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
