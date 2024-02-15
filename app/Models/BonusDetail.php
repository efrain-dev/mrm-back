<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "detail_bonus";
    protected $primaryKey = 'id';
    public $timestamps = false;
}
