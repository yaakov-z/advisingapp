<?php

/*
<COPYRIGHT>

Copyright © 2022-2023, Canyon GBS LLC

All rights reserved.

This file is part of a project developed using Laravel, which is an open-source framework for PHP.
Canyon GBS LLC acknowledges and respects the copyright of Laravel and other open-source
projects used in the development of this solution.

This project is licensed under the Affero General Public License (AGPL) 3.0.
For more details, see https://github.com/canyongbs/assistbycanyongbs/blob/main/LICENSE.

Notice:
- The copyright notice in this file and across all files and applications in this
 repository cannot be removed or altered without violating the terms of the AGPL 3.0 License.
- The software solution, including services, infrastructure, and code, is offered as a
 Software as a Service (SaaS) by Canyon GBS LLC.
- Use of this software implies agreement to the license terms and conditions as stated
 in the AGPL 3.0 License.

For more information or inquiries please visit our website at
https://www.canyongbs.com or contact us via email at legal@canyongbs.com.

</COPYRIGHT>
*/

namespace Assist\ServiceManagement\Filament\Resources\ServiceRequestResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Columns\IdColumn;
use App\Filament\Resources\UserResource;
use Assist\ServiceManagement\Models\ServiceRequest;
use App\Filament\Resources\RelationManagers\RelationManager;

class AssignedToRelationManager extends RelationManager
{
    protected static string $relationship = 'assignedTo';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                IdColumn::make(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name'),
            ])
            ->paginated(false)
            ->headerActions([
                // TODO: Figure out how to make it so this only displays on the edit page
                Tables\Actions\Action::make('reassign-service-request')
                    ->label('Reassign Service Request')
                    ->color('gray')
                    ->action(function (array $data): void {
                        /** @var ServiceRequest $serviceRequest */
                        $serviceRequest = $this->ownerRecord;

                        $serviceRequest->assignedTo()->associate($data['userId'])->save();
                    })
                    ->form([
                        Forms\Components\Select::make('userId')
                            ->label('Assigned User')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => User::whereRaw('LOWER(name) LIKE ? ', ['%' . str($search)->lower() . '%'])->pluck('name', 'id')->toArray())
                            ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->name)
                            ->placeholder('Search for and select a User')
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (User $user) => UserResource::getUrl('view', ['record' => $user])),
            ]);
    }
}
