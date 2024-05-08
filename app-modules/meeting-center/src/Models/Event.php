<?php

/*
<COPYRIGHT>

    Copyright © 2016-2024, Canyon GBS LLC. All rights reserved.

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

namespace AdvisingApp\MeetingCenter\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contracts\CanBeReplicated;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperEvent
 */
class Event extends BaseModel implements CanBeReplicated
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'location',
        'capacity',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function eventRegistrationForm(): HasOne
    {
        return $this->hasOne(EventRegistrationForm::class, 'event_id');
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(EventAttendee::class, 'event_id');
    }

    public function replicateRelatedData(Model $original): void
    {
        $this->replicateEventRegistrationForm($original);
        $stepMap = $this->replicateSteps($original);
        $fieldMap = $this->replicateFields($original, $stepMap);
        $this->updateStepContent($fieldMap);
    }

    protected function replicateSteps(Model $original): array
    {
        $stepMap = [];

        $original->eventRegistrationForm->steps()->each(function (EventRegistrationFormStep $step) use (&$stepMap) {
            $newStep = $step->replicate();
            $newStep->form_id = $this->eventRegistrationForm->id;
            $newStep->save();

            $stepMap[$step->id] = $newStep->id;
        });

        return $stepMap;
    }

    protected function replicateFields(Model $original, array $stepMap): array
    {
        $fieldMap = [];

        $original->eventRegistrationForm->fields()->each(function (EventRegistrationFormField $field) use (&$fieldMap, $stepMap) {
            $newField = $field->replicate();
            $newField->form_id = $this->eventRegistrationForm->id;
            $newField->step_id = $stepMap[$field->step_id] ?? null;
            $newField->save();

            $fieldMap[$field->id] = $newField->id;
        });

        return $fieldMap;
    }

    protected function updateStepContent(array $fieldMap): void
    {
        $this->eventRegistrationForm->steps()->each(function (EventRegistrationFormStep $step) use ($fieldMap) {
            $content = $step->content;
            $step->update([
                'content' => $this->replaceIdsInContent($content, $fieldMap),
            ]);
        });
    }

    protected function replaceIdsInContent(&$content, $fieldMap)
    {
        if (is_array($content)) {
            foreach ($content as $key => &$value) {
                if (is_array($value)) {
                    $this->replaceIdsInContent($value, $fieldMap);
                } else {
                    if ($key === 'id' && isset($fieldMap[$value])) {
                        $value = $fieldMap[$value];
                    }
                }
            }
        }

        return $content;
    }

    protected function replicateEventRegistrationForm(Event $original): void
    {
        $eventRegistrationForm = $original->eventRegistrationForm->replicate();
        $eventRegistrationForm->event_id = $this->id;

        $eventRegistrationForm->save();
    }
}
