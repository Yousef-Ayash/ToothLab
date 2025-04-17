<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tooth extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'name',
        'position',
        'description',
    ];

    /**
     * Get the orders associated with this tooth
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
