<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Price extends Model
{
    protected $table = 'prices';
    protected $fillable = ['price', 'currency', 'entity_id', 'entity_type'];

    public function entity(): MorphTo
    {
        return $this->morphTo('entity');
    }
}
