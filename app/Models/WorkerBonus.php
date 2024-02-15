<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerBonus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "worker_bonus";
    protected $primaryKey = 'id';
    public $timestamps = false;
}
