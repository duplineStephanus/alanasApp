<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory; 

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function items () {
        return $this->hasMany(CartItem::class);
    }

    public function product () {
        return $this->belongsTo(Product::class);
    }

}
