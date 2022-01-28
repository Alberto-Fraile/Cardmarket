<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
   use HasFactory;

   public function card()
   {
      return $this->hasMany(Card::class, 'collections_id');
   }

   public function cards()
   {
      return $this->belongsToMany(Card::class, 'card_collection', 'collections_id', 'cards_id');
   } 
}
