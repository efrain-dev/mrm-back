<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "payroll";
    protected $primaryKey = 'id';
    public $timestamps = false;
}
