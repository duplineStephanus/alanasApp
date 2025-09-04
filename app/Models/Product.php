<?php

namespace App\Models;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory; 

    protected $fillable = [
        'name',
        'type',
        'description',
    ];

    public function variants(){
        return $this->hasMany(ProductVariant::class);
    }
}
