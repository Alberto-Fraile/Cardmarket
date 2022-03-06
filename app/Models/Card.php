<?php

namespace App\Models;

//use Illuminate\Support\Str;
//use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $table = 'cards';
    protected $fillable = ['id','name','description'];
    //protected $hidden = ['created_at','updated_at'];
    
    public function colections(){
        return $this->belongsToMany(Colection::class);
    }

}
