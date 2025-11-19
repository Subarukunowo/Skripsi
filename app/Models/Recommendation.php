<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommendation extends Model
{
    protected $fillable = ['team_id', 'recommended_formation', 'confidence_score'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}