<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'value',
        'level_id'
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}
