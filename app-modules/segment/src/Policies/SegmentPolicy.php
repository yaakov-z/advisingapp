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

namespace AdvisingApp\Segment\Policies;

use AdvisingApp\Authorization\Enums\LicenseType;
use AdvisingApp\Segment\Models\Segment;
use App\Concerns\PerformsLicenseChecks;
use App\Models\Authenticatable;
use App\Policies\Contracts\PerformsChecksBeforeAuthorization;
use Illuminate\Auth\Access\Response;

class SegmentPolicy implements PerformsChecksBeforeAuthorization
{
    use PerformsLicenseChecks;

    public function before(Authenticatable $authenticatable): ?Response
    {
        if (! is_null($response = $this->hasAnyLicense($authenticatable, [LicenseType::RetentionCrm, LicenseType::RecruitmentCrm]))) {
            return $response;
        }

        return null;
    }

    public function viewAny(Authenticatable $authenticatable): Response
    {
        return $authenticatable->canOrElse(
            abilities: 'segment.view-any',
            denyResponse: 'You do not have permission to view segments.'
        );
    }

    public function view(Authenticatable $authenticatable, Segment $segment): Response
    {
        if (! $authenticatable->hasLicense($segment->model?->class()::getLicenseType())) {
            return Response::deny('You do not have permission to view this segment.');
        }

        return $authenticatable->canOrElse(
            abilities: ["segment.{$segment->getKey()}.view"],
            denyResponse: 'You do not have permission to view this segment.'
        );
    }

    public function create(Authenticatable $authenticatable): Response
    {
        return $authenticatable->canOrElse(
            abilities: 'segment.create',
            denyResponse: 'You do not have permission to create segments.'
        );
    }

    public function update(Authenticatable $authenticatable, Segment $segment): Response
    {
        if (! $authenticatable->hasLicense($segment->model?->class()::getLicenseType())) {
            return Response::deny('You do not have permission to update this segment.');
        }

        return $authenticatable->canOrElse(
            abilities: ["segment.{$segment->getKey()}.update"],
            denyResponse: 'You do not have permission to update this segment.'
        );
    }

    public function delete(Authenticatable $authenticatable, Segment $segment): Response
    {
        if (! $authenticatable->hasLicense($segment->model?->class()::getLicenseType())) {
            return Response::deny('You do not have permission to delete this segment.');
        }

        if ($segment->campaigns()->exists()) {
            return Response::deny('This population segment is associated with a campaign. Please remove the campaign before attempting to remove the segment.');
        }

        return $authenticatable->canOrElse(
            abilities: ["segment.{$segment->getKey()}.delete"],
            denyResponse: 'You do not have permission to delete this segment.'
        );
    }

    public function restore(Authenticatable $authenticatable, Segment $segment): Response
    {
        if (! $authenticatable->hasLicense($segment->model?->class()::getLicenseType())) {
            return Response::deny('You do not have permission to restore this segment.');
        }

        return $authenticatable->canOrElse(
            abilities: ["segment.{$segment->getKey()}.restore"],
            denyResponse: 'You do not have permission to restore this segment.'
        );
    }

    public function forceDelete(Authenticatable $authenticatable, Segment $segment): Response
    {
        if (! $authenticatable->hasLicense($segment->model?->class()::getLicenseType())) {
            return Response::deny('You do not have permission to permanently delete this segment.');
        }

        if ($segment->campaigns()->exists()) {
            return Response::deny('This population segment is associated with a campaign. Please remove the campaign before attempting to remove the segment.');
        }

        return $authenticatable->canOrElse(
            abilities: ["segment.{$segment->getKey()}.force-delete"],
            denyResponse: 'You do not have permission to permanently delete this segment.'
        );
    }
}
