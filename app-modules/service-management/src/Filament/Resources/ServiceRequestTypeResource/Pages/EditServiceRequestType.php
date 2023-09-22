<?php

namespace Assist\ServiceManagement\Filament\Resources\ServiceRequestTypeResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestTypeResource;

class EditServiceRequestType extends EditRecord
{
    protected static string $resource = ServiceRequestTypeResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->string(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
