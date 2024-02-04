<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "worker";
    protected $primaryKey = 'id';
    public $timestamps = false;
}
