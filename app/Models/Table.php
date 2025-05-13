<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Table extends Model
{
    protected $table = 'tables';
    protected $fillable = ['name', 'seats', 'hall'];

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'entity');
    }

    public function price(): MorphOne
    {
        return $this->morphOne(Price::class, 'entity');
    }
}
