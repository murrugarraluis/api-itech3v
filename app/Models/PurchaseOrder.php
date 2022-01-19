<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'date_required',
        'date_agreed',
        'importance',
        'type_purchase_order',
        'document_number',
        'status',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
    public function materials()
    {
        return $this->belongsToMany(Material::class)->withPivot('quantity','price');
    }
}
