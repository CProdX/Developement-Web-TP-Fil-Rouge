<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'email',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public const UPDATED_AT = null;

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'client_id');
    }
}

