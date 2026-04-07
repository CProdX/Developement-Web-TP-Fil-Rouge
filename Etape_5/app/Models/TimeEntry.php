<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    protected $table = 'temps_passes';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'duree_minutes',
        'commentaire',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public const UPDATED_AT = null;

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

