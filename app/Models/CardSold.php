<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardSold extends Model
{
    use HasFactory;

    protected $table = 'card_solds';

    protected $hidden = ['id','created_at','updated_at'];
}
