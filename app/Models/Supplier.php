<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'type_document',
        'number_document',
        'name',
        'lastname'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
