<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardSold extends Model
{
    use HasFactory;

    public function solds(){
        return $this -> hasMany(Card::class, 'card_asociate');
    }

    protected $hidden = ['id','created_at','updated_at'];
}
