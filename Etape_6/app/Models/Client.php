<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'email',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
