<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'client_id',
        'contrat_id',
        'nom',
        'description',
        'statut',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public const UPDATED_AT = null;

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contrat_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'project_id');
    }
}

