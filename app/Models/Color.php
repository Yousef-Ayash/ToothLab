<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
    ];

    /**
     * Get the procedures associated with this color
     */
    public function procedures()
    {
        return $this->hasMany(Procedure::class);
    }

    /**
     * Get the orders using this color for teeth
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
