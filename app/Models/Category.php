<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function category(): HasMany
    {
        return $this->hasMany(Material::class);
    }
}
