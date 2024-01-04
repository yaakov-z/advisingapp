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

namespace AdvisingApp\CaseloadManagement\Policies;

use App\Models\Authenticatable;
use Illuminate\Auth\Access\Response;
use AdvisingApp\Prospect\Models\Prospect;
use AdvisingApp\StudentDataModel\Models\Student;
use AdvisingApp\CaseloadManagement\Models\Caseload;

class CaseloadPolicy
{
    public function before(Authenticatable $authenticatable): ?Response
    {
        if (! $authenticatable->hasAnyLicense([Student::getLicenseType(), Prospect::getLicenseType()])) {
            return Response::deny('You do not have permission to view students or prospects.');
        }

        return null;
    }

    public function viewAny(Authenticatable $authenticatable): Response
    {
        return $authenticatable->canOrElse(
            abilities: 'caseload.view-any',
            denyResponse: 'You do not have permission to view caseloads.'
        );
    }

    public function view(Authenticatable $authenticatable, Caseload $caseload): Response
    {
        if (! $authenticatable->hasLicense($caseload->model?->class()::getLicenseType())) {
            return Response::deny('You do not have permission to view this caseload.');
        }

        return $authenticatable->canOrElse(
            abilities: ['caseload.*.view', "caseload.{$caseload->id}.view"],
            denyResponse: 'You do not have permission to view this caseload.'
        );
    }

    public function create(Authenticatable $authenticatable): Response
    {
        return $authenticatable->canOrElse(
            abilities: 'caseload.create',
            denyResponse: 'You do not have permission to create caseloads.'
        );
    }

    public function update(Authenticatable $authenticatable, Caseload $caseload): Response
    {
        if (! $authenticatable->hasLicense($caseload->model?->class()::getLicenseType())) {
            return Response::deny('You do not have permission to update this caseload.');
        }

        return $authenticatable->canOrElse(
            abilities: ['caseload.*.update', "caseload.{$caseload->id}.update"],
            denyResponse: 'You do not have permission to update this caseload.'
        );
    }

    public function delete(Authenticatable $authenticatable, Caseload $caseload): Response
    {
        if (! $authenticatable->hasLicense($caseload->model?->class()::getLicenseType())) {
            return Response::deny('You do not have permission to delete this caseload.');
        }

        return $authenticatable->canOrElse(
            abilities: ['caseload.*.delete', "caseload.{$caseload->id}.delete"],
            denyResponse: 'You do not have permission to delete this caseload.'
        );
    }

    public function restore(Authenticatable $authenticatable, Caseload $caseload): Response
    {
        if (! $authenticatable->hasLicense($caseload->model?->class()::getLicenseType())) {
            return Response::deny('You do not have permission to restore this caseload.');
        }

        return $authenticatable->canOrElse(
            abilities: ['caseload.*.restore', "caseload.{$caseload->id}.restore"],
            denyResponse: 'You do not have permission to restore this caseload.'
        );
    }

    public function forceDelete(Authenticatable $authenticatable, Caseload $caseload): Response
    {
        if (! $authenticatable->hasLicense($caseload->model?->class()::getLicenseType())) {
            return Response::deny('You do not have permission to permanently delete this caseload.');
        }

        return $authenticatable->canOrElse(
            abilities: ['caseload.*.force-delete', "caseload.{$caseload->id}.force-delete"],
            denyResponse: 'You do not have permission to permanently delete this caseload.'
        );
    }
}
