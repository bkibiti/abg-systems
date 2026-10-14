<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function stockTracking()
    {
        return $this->hasMany(StockTracking::class, 'updated_by');
    }

    public function stockAdjustment()
    {
        return $this->hasMany(StockAdjustment::class, 'created_by');
    }

    public function stockIssue()
    {
        return $this->hasMany(StockIssue::class, 'updated_by');
    }

    public function issueReturn()
    {
        return $this->hasMany(IssueReturn::class, 'returned_by');
    }

    public function sale()
    {
        return $this->hasOne(Sale::class, 'created_by');
    }

    public function expense()
    {
        return $this->hasMany(Expense::class, 'updated_by');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'ordered_by');
    }

}
