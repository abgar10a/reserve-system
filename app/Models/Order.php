<?php

namespace App\Models;

use App\Enums\OrderStatus;
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
        return $this->belongsTo(User::class, 'user', 'id');
    }

    public function payment(): MorphOne
    {
        return $this->hasOne(Payment::class, 'order', 'id');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [OrderStatus::PROCESS->value, OrderStatus::WAITING->value]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', OrderStatus::COMPLETED->value);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', OrderStatus::CANCELLED->value);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('entity_type', $type);
    }
}
