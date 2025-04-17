<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Procedure extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'cost',
        'color_id',
    ];

    /**
     * Get the color associated with this procedure
     */
    public function colors(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Get the steps for this procedure
     */
    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    /**
     * Get the orders using this procedure
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
