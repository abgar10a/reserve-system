<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Hall extends Model
{
    protected $table = 'halls';
    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];


    public function tables(): HasMany
    {
        return $this->hasMany(Table::class, 'hall', 'id');
    }

    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'entity');
    }

    public function price(): MorphMany
    {
        return $this->morphMany(Price::class, 'entity');
    }
}
