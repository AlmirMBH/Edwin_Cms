<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /*
     * Polymorphic relationship - Inverse
     */
    public function posts() {

        return $this->morphedByMany('App\Post', 'taggable');
    }
    
    public function videos() {
        
        return$this->morphedByMany('App\Video', 'taggable');
    }
}
