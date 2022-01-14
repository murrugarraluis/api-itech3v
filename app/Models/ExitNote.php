<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExitNote extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'date',
        'type_exit',
        'comment',
        'document_number'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function materials()
    {
        return $this->belongsToMany(Material::class)->withPivot('quantity');
    }
}