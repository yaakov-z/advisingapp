<?php

namespace Assist\Task\Filament\Concerns;

use Assist\Prospect\Models\Prospect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Assist\AssistDataModel\Models\Student;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect\Type;

trait TaskEditForm
{
    public function editFormFields(): array
    {
        return [
            TextInput::make('description')
                ->label('Description')
                ->required()
                ->string(),
            DateTimePicker::make('due')
                ->label('Due Date')
                ->native(false),
            Select::make('assigned_to')
                ->label('Assigned To')
                ->relationship('assignedTo', 'name')
                ->nullable()
                ->searchable(['name', 'email'])
                ->default(auth()->id()),
            MorphToSelect::make('concern')
                ->label('Concern')
                ->searchable()
                ->preload()
                ->types([
                    Type::make(Student::class)
                        ->titleAttribute(Student::displayNameKey()),
                    Type::make(Prospect::class)
                        ->titleAttribute(Prospect::displayNameKey()),
                ]),
        ];
    }
}
