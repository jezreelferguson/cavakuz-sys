<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'location',
        'company',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(CustomerNotification::class);
    }
}
