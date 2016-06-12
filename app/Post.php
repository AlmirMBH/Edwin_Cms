<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * If the name of the model is Post, then Laravel predicts that wie have table in database called posts with row id as a PK
     * If wie want some other name for our table then wie have to tell that to Laravel:
        | protected $table = 'nameOfTheTable';
        | protected  $primaryKey = 'nameOFTheRow';
    */

    //allow mass assignment for this columns
    protected $fillable = [
        'title',
        'content',
    ];
    
    public function user() {
        
        return $this->belongsTo('App\User');
    }

    public function photos() {

        return $this->morphMany('App\Photo', 'imageable');
    }

    /*
     * Polymorphic relation Many to Many
    */
    public function tags() {
        
        return $this->morphToMany('App\Tag', 'taggable');
    }

}