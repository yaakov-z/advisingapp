<?php

/*
<COPYRIGHT>

    Copyright © 2022-2023, Canyon GBS LLC. All rights reserved.

    Advising App™ is licensed under the Elastic License 2.0. For more details,
    see https://github.com/canyongbs/advisingapp/blob/main/LICENSE.

    Notice:

    - You may not provide the software to third parties as a hosted or managed
      service, where the service provides users with access to any substantial set of
      the features or functionality of the software.
    - You may not move, change, disable, or circumvent the license key functionality
      in the software, and you may not remove or obscure any functionality in the
      software that is protected by the license key.
    - You may not alter, remove, or obscure any licensing, copyright, or other notices
      of the licensor in the software. Any use of the licensor’s trademarks is subject
      to applicable law.
    - Canyon GBS LLC respects the intellectual property rights of others and expects the
      same in return. Canyon GBS™ and Advising App™ are registered trademarks of
      Canyon GBS LLC, and we are committed to enforcing and protecting our trademarks
      vigorously.
    - The software solution, including services, infrastructure, and code, is offered as a
      Software as a Service (SaaS) by Canyon GBS LLC.
    - Use of this software implies agreement to the license terms and conditions as stated
      in the Elastic License 2.0.

    For more information or inquiries please visit our website at
    https://www.canyongbs.com or contact us via email at legal@canyongbs.com.

</COPYRIGHT>
*/

namespace Assist\ServiceManagement\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Assist\ServiceManagement\Models\ServiceRequest;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestResource\Pages\EditServiceRequest;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestResource\Pages\ViewServiceRequest;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestResource\Pages\ListServiceRequests;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestResource\Pages\CreateServiceRequest;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestResource\Pages\ServiceRequestTimeline;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestResource\Pages\ManageServiceRequestUser;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestResource\Pages\ManageServiceRequestUpdate;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestResource\Pages\ManageServiceRequestInteraction;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static ?string $navigationLabel = 'Service Management';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Productivity Tools';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Service Management';

    protected static ?string $pluralLabel = 'Service Management';

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewServiceRequest::class,
            EditServiceRequest::class,
            ManageServiceRequestUser::class,
            ManageServiceRequestUpdate::class,
            ManageServiceRequestInteraction::class,
            ServiceRequestTimeline::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceRequests::route('/'),
            'manage-users' => ManageServiceRequestUser::route('/{record}/users'),
            'manage-service-request-updates' => ManageServiceRequestUpdate::route('/{record}/updates'),
            'manage-interactions' => ManageServiceRequestInteraction::route('/{record}/interactions'),
            'create' => CreateServiceRequest::route('/create'),
            'view' => ViewServiceRequest::route('/{record}'),
            'edit' => EditServiceRequest::route('/{record}/edit'),
            'timeline' => ServiceRequestTimeline::route('/{record}/timeline'),
        ];
    }
}
