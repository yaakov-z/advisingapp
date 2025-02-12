<?php

/*
<COPYRIGHT>

    Copyright © 2016-2025, Canyon GBS LLC. All rights reserved.

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

namespace AdvisingApp\Engagement\GraphQL\Mutations;

use AdvisingApp\Engagement\Actions\CreateEngagement;
use AdvisingApp\Engagement\Actions\GenerateTipTapBodyJson;
use AdvisingApp\Engagement\DataTransferObjects\EngagementCreationData;
use AdvisingApp\Engagement\Models\Engagement;
use AdvisingApp\Notification\Enums\NotificationChannel;
use AdvisingApp\Prospect\Models\Prospect;
use AdvisingApp\StudentDataModel\Models\Student;
use App\Enums\Integration;
use App\Exceptions\IntegrationException;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SendSMS
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Engagement
    {
        if (Integration::Twilio->isOff()) {
            throw IntegrationException::make(Integration::Twilio);
        }

        $morph = Relation::getMorphedModel($args['recipient_type']);

        $mergeTags = collect(Engagement::getMergeTags($morph))
            ->map(fn (string $tag): string => "{{ {$tag} }}")
            ->toArray();

        $body = str($args['body'])
            ->markdown([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ])
            ->toString();

        $body = app(GenerateTipTapBodyJson::class)(body: $body, mergeTags: $mergeTags);

        $engagement = app(CreateEngagement::class)->execute(new EngagementCreationData(
            user: User::findOrFail($args['user_id']),
            recipient: match ($morph) {
                Student::class => Student::findOrFail($args['recipient_id']),
                Prospect::class => Prospect::findOrFail($args['recipient_id']),
            },
            channel: NotificationChannel::Sms,
            body: $body,
            scheduledAt: Carbon::parse($args['scheduled_at']),
        ));

        return $engagement->refresh();
    }
}
