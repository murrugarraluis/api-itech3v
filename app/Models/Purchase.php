<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'way_to_pay',
        'type_document',
        'number',
        'type_purchase',
        'status',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function materials()
    {
        return $this->belongsToMany(Material::class)->withPivot('quantity','price');
    }
}
