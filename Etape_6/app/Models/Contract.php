<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    protected $table = 'contrats';
    protected $guarded = [];

    public const UPDATED_AT = null;

    protected $casts = [
        'included_hours' => 'float',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'contrat_id');
    }

    public function getNameAttribute(): string
    {
        return (string) ($this->attributes['label'] ?? '');
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['label'] = $value;
    }

    public function getIncludedHoursAttribute($value): float
    {
        return (float) ($this->attributes['heures_incluses'] ?? $value ?? 0);
    }

    public function setIncludedHoursAttribute($value): void
    {
        $this->attributes['heures_incluses'] = $value;
    }
}
