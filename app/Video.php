<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /*
    * Polymorphic relation Many to Many
    */
    public function tags() {

        return $this->morphToMany('App\Tag', 'taggable');
    }
}
