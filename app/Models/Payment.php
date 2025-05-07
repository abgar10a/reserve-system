<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = ['order', 'status', 'paid_at'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
