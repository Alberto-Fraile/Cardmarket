<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $hidden = ['created_at','updated_at'];
    
    public function colections(){
        return $this->belongsToMany(Colection::class,'card_colection');
    }

}

  // public function collection()
  // {
  //   return $this->belongsTo(Collection::class);
  // }
  //   public function collections()
  // {
  //   return $this->belongsToMany(Collection::class, 'card_collection', 'collections_id', 'cards_id');
  // }