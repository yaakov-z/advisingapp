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

namespace AdvisingApp\IntegrationGoogleRecaptcha\Rules;

use AdvisingApp\IntegrationGoogleRecaptcha\Settings\GoogleRecaptchaSettings;
use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaTokenValid implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            $fail('The recaptcha token was not provided.');
        }

        $settings = app(GoogleRecaptchaSettings::class);

        try {
            $response = Http::asForm()
                ->retry(3, 100)
                ->post(config('services.google_recaptcha.url'), [
                    'secret' => $settings->secret_key,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ])
                ->throw();

            // TODO Figure out how we actually want to handle low scores
            if ($response->json('score') < 0.5) {
                $fail('The recaptcha score was too low.');
            }
        } catch (Exception $e) {
            $fail('The recaptcha token was invalid.');
        }
    }
}
