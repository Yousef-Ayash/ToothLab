<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'procedure_id',
        'name',
        'description',
        'order',
    ];

    /**
     * Get the procedure this step belongs to
     */
    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    /**
     * Get the employees assigned to this step
     */
    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_step', 'step_id', 'user_id');
    }

    /**
     * Get the order steps related to this step
     */
    public function orderSteps()
    {
        return $this->hasMany(OrderStep::class);
    }
}
