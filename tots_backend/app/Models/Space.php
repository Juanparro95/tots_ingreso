<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Space extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'description',
        'capacity',
        'location',
        'image_url',
        'hourly_rate',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'hourly_rate' => 'float',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
