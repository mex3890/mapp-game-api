<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Professionals extends Model
{
    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class)->withPivot('patient_id', 'professional_id');
    }
}
