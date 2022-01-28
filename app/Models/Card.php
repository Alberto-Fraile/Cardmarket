<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
  use HasFactory;

  public function collection()
  {
    return $this->belongsTo(Collection::class);
    
    return $this->belongsToMany(Collection::class, 'card_collection', 'collections_id', 'cards_id');
  }
}
