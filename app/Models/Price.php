<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Price extends Model
{
    protected $table = 'prices';
    protected $fillable = ['amount', 'currency', 'entity_id', 'entity_type'];
    protected $with = ['currency'];

    public function entity(): MorphTo
    {
        return $this->morphTo('entity');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency', 'id');
    }
}
