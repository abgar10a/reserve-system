<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Hall extends Model
{
    protected $table = 'halls';
    protected $fillable = ['name'];

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class, 'hall', 'id');
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
