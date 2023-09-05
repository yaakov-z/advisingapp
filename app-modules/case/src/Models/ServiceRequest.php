<?php

namespace Assist\Case\Models;

use Eloquent;
use App\Models\User;
use DateTimeInterface;
use App\Models\BaseModel;
use App\Models\Institution;
use Assist\Audit\Models\Audit;
use Illuminate\Support\Carbon;
use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Assist\Notifications\Models\Contracts\Subscribable;
use Assist\Case\Database\Factories\ServiceRequestFactory;
use Assist\Audit\Models\Concerns\Auditable as AuditableTrait;
use Assist\Notifications\Models\Contracts\CanTriggerAutoSubscription;

/**
 * Assist\Case\Models\ServiceRequest
 *
 * @property string $id
 * @property int $casenumber
 * @property string|null $respondent_type
 * @property string|null $respondent_id
 * @property string|null $close_details
 * @property string|null $res_details
 * @property string|null $institution_id
 * @property string|null $status_id
 * @property string|null $type_id
 * @property string|null $priority_id
 * @property string|null $assigned_to_id
 * @property string|null $created_by_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User|null $assignedTo
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, ServiceRequestUpdate> $caseUpdates
 * @property-read int|null $case_updates_count
 * @property-read User|null $createdBy
 * @property-read Institution|null $institution
 * @property-read ServiceRequestPriority|null $priority
 * @property-read Model|Eloquent $respondent
 * @property-read ServiceRequestStatus|null $status
 * @property-read ServiceRequestType|null $type
 *
 * @method static ServiceRequestFactory factory($count = null, $state = [])
 * @method static Builder|ServiceRequest newModelQuery()
 * @method static Builder|ServiceRequest newQuery()
 * @method static Builder|ServiceRequest onlyTrashed()
 * @method static Builder|ServiceRequest query()
 * @method static Builder|ServiceRequest whereAssignedToId($value)
 * @method static Builder|ServiceRequest whereCasenumber($value)
 * @method static Builder|ServiceRequest whereCloseDetails($value)
 * @method static Builder|ServiceRequest whereCreatedAt($value)
 * @method static Builder|ServiceRequest whereCreatedById($value)
 * @method static Builder|ServiceRequest whereDeletedAt($value)
 * @method static Builder|ServiceRequest whereId($value)
 * @method static Builder|ServiceRequest whereInstitutionId($value)
 * @method static Builder|ServiceRequest wherePriorityId($value)
 * @method static Builder|ServiceRequest whereResDetails($value)
 * @method static Builder|ServiceRequest whereRespondentId($value)
 * @method static Builder|ServiceRequest whereRespondentType($value)
 * @method static Builder|ServiceRequest whereStatusId($value)
 * @method static Builder|ServiceRequest whereTypeId($value)
 * @method static Builder|ServiceRequest whereUpdatedAt($value)
 * @method static Builder|ServiceRequest withTrashed()
 * @method static Builder|ServiceRequest withoutTrashed()
 *
 * @mixin Eloquent
 */
class ServiceRequest extends BaseModel implements Auditable, CanTriggerAutoSubscription
{
    use SoftDeletes;
    use PowerJoins;
    use AuditableTrait;
    use HasUuids;

    protected $fillable = [
        'service_request_number',
        'respondent_type',
        'respondent_id',
        'institution_id',
        'status_id',
        'type_id',
        'priority_id',
        'assigned_to_id',
        'close_details',
        'res_details',
        'created_by_id',
    ];

    public function getSubscribable(): ?Subscribable
    {
        return $this->respondent instanceof Subscribable ? $this->respondent : null;
    }

    public function respondent(): MorphTo
    {
        return $this->morphTo(
            name: 'respondent',
            type: 'respondent_type',
            id: 'respondent_id',
        );
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function serviceRequestUpdates(): HasMany
    {
        return $this->hasMany(ServiceRequestUpdate::class, 'service_request_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ServiceRequestStatus::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ServiceRequestType::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(ServiceRequestPriority::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format(config('project.datetime_format') ?? 'Y-m-d H:i:s');
    }
}
