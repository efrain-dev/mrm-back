<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollBonus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "bonus_payroll";
    protected $primaryKey = 'id';
    public $timestamps = false;
}
