<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'date_agreed',
        'way_to_pay',
        'type_quotation',
        'document_number',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function materials()
    {
        return $this->belongsToMany(Material::class)->withPivot('quantity','price');
    }
}
