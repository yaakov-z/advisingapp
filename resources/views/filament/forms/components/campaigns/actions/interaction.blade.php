{{--
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
--}}
@php
    use Carbon\Carbon;
    use Assist\Division\Models\Division;
    use Assist\Interaction\Models\InteractionType;
    use Assist\Interaction\Models\InteractionStatus;
    use Assist\Interaction\Models\InteractionDriver;
    use Assist\Interaction\Models\InteractionOutcome;
    use Assist\Interaction\Models\InteractionCampaign;
    use Assist\Interaction\Models\InteractionRelation;
@endphp

<x-filament::fieldset>
    <x-slot name="label">
        Interaction
    </x-slot>

    <dl class="max-w-md divide-y divide-gray-200 text-gray-900 dark:divide-gray-700 dark:text-white">
        <div class="flex flex-col pb-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Campaign</dt>
            <dd class="text-sm font-semibold">{{ InteractionCampaign::find($action['interaction_campaign_id'])?->name }}
            </dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Driver</dt>
            <dd class="text-sm font-semibold">{{ InteractionDriver::find($action['interaction_driver_id'])?->name }}</dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Division</dt>
            <dd class="text-sm font-semibold">{{ Division::find($action['division_id'])?->name }}</dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Outcome</dt>
            <dd class="text-sm font-semibold">{{ InteractionOutcome::find($action['interaction_outcome_id'])?->name }}
            </dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Relation</dt>
            <dd class="text-sm font-semibold">{{ InteractionRelation::find($action['interaction_relation_id'])?->name }}
            </dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Status</dt>
            <dd class="text-sm font-semibold">{{ InteractionStatus::find($action['interaction_status_id'])?->name }}
            </dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Type</dt>
            <dd class="text-sm font-semibold">{{ InteractionType::find($action['interaction_type_id'])?->name }}
            </dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Start Time</dt>
            <dd class="text-sm font-semibold">{{ Carbon::parse($action['start_datetime'])->format('m/d/Y H:i:s') }}</dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">End Time</dt>
            <dd class="text-sm font-semibold">{{ Carbon::parse($action['end_datetime'])->format('m/d/Y H:i:s') }}</dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Subject</dt>
            <dd class="text-sm font-semibold">{{ $action['subject'] }}</dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Description</dt>
            <dd class="text-sm font-semibold">{{ $action['description'] }}</dd>
        </div>
        <div class="flex flex-col pt-3">
            <dt class="mb-1 text-sm text-gray-500 dark:text-gray-400">Execute At</dt>
            <dd class="text-sm font-semibold">{{ Carbon::parse($action['execute_at'])->format('m/d/Y H:i:s') }}</dd>
        </div>
    </dl>

</x-filament::fieldset>
