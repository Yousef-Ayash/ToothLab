<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'doctor_id',
        'center_name',
        'patient_name',
        'tooth_id',
        'procedure_id',
        'color_id',
        'notes',
        'status',
        'is_paid',
        'total_cost',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_paid' => 'boolean',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Get the doctor who created this order
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the tooth associated with this order
     */
    public function tooth()
    {
        return $this->belongsTo(Tooth::class);
    }

    /**
     * Get the procedure associated with this order
     */
    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    /**
     * Get the color associated with this order
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Get the order steps for this order
     */
    public function orderSteps()
    {
        return $this->hasMany(OrderStep::class);
    }
}
