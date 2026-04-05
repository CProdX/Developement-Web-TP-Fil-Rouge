<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    protected $guarded = [];

    protected $table = 'time_entries';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'hours',
        'note',
    ];

    protected $casts = [
        'hours' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
