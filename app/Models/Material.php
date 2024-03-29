<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name','minimum_stock'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function mark(): BelongsTo
    {
        return $this->belongsTo(Mark::class);
    }

    public function measure_unit(): BelongsTo
    {
        return $this->belongsTo(MeasureUnit::class);
    }

    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(Request::class);
    }
    public function warehouses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class)->withPivot('quantity');
    }
    public function exit_notes(): BelongsToMany
    {
        return $this->belongsToMany(ExitNote::class);
    }
}
