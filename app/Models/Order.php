<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['user', 'start', 'end', 'status', 'entity_id', 'entity_type'];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function price(): MorphOne
    {
        return $this->morphOne(Price::class, 'entity');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
