<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tour extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'travel_id',
        'name',
        'starting_date',
        'ending_date',
        'price',
    ];

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }

    // There's a well-known truth in the DB schema world: don't store money in floats.
    // https://laraveldaily.com/post/money-laravel-php-best-practices
    // requirements:
    // ours prices are integer multiplied by 100: for example, €999 euro will be 99900, but,
    // when returned to Frontends, they will be formatted (99900 / 100);
    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }
}
