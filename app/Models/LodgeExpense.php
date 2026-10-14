<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LodgeExpense extends Model
{
    protected $table = 'lodge_expenses';
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(AccExpenseCategory::class, 'expense_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
