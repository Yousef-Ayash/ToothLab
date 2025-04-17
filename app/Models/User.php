<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'center_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if the user is a doctor
     *
     * @return bool
     */
    public function isDoctor()
    {
        return $this->role === 'doctor';
    }

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is an employee
     *
     * @return bool
     */
    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    /**
     * Get the orders created by this doctor
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'doctor_id');
    }

    /**
     * Get the steps this employee is assigned to
     */
    public function steps()
    {
        return $this->belongsToMany(Step::class, 'employee_step');
    }

    /**
     * Get the order steps this employee has worked on
     */
    public function orderSteps()
    {
        return $this->hasMany(OrderStep::class, 'employee_id');
    }
}
