<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = [
        'name',
        'description',
    ];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
