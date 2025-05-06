<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Currency extends Model
{
    protected $table = 'currency';

    protected $fillable = ['name', 'code', 'symbol'];

    public function prices(): BelongsToMany
    {
        return $this->belongsToMany(Price::class);
    }
}
