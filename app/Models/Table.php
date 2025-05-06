<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Table extends Model
{
    protected $table = 'tables';
    protected $fillable = ['name', 'seats', 'hall'];

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function orders(): MorphToMany
    {
        return $this->morphToMany(Order::class, 'entity');
    }

    public function price(): MorphToMany
    {
        return $this->morphToMany(Price::class, 'entity');
    }
}
