<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "config";
    protected $primaryKey = 'id';
    public $timestamps = false;
}
