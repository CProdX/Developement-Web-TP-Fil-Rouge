<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $guarded = [];

    public const UPDATED_AT = null;

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contrat_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'project_id');
    }

    public function getNameAttribute(): string
    {
        return (string) ($this->attributes['nom'] ?? '');
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['nom'] = $value;
    }

    public function getStatusAttribute(): string
    {
        return (string) ($this->attributes['statut'] ?? '');
    }

    public function setStatusAttribute(string $value): void
    {
        $this->attributes['statut'] = $value;
    }

    public function getContractIdAttribute()
    {
        return $this->attributes['contrat_id'] ?? null;
    }

    public function setContractIdAttribute($value): void
    {
        $this->attributes['contrat_id'] = $value;
    }
}
