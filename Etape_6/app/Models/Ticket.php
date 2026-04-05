<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'status',
        'priority',
        'billing_type',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class, 'ticket_id');
    }

    public function getHoursSpentAttribute(): float
    {
        if ($this->relationLoaded('timeEntries')) {
            return round((float) $this->timeEntries->sum('hours'), 2);
        }

        return round((float) $this->timeEntries()->sum('hours'), 2);
    }
}
