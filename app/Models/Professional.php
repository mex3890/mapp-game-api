<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static where(string $string, int $professional_id)
 */
class Professional extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'company_id',
        'state',
        'license',
        'validated_at',
        'created_at'
    ];

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class, 'professional_patient', 'professional_id')
            ->withTimestamps();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
