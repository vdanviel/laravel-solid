<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardItem extends Model
{
    use HasFactory;

    protected $table = 'card_item';

    protected $fillable = [
        'product_id',
        'card_id',
        'amount',
        'quantity'
    ];
    
}
