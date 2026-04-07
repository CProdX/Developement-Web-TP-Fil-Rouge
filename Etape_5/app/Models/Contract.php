<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contrats';

    protected $fillable = [
        'client_id',
        'label',
        'heures_incluses',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'heures_incluses' => 'float',
    ];

    public const UPDATED_AT = null;

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}

