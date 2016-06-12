<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //one to one relationship 
    public function post() {

        return $this->hasOne('App\Post'); 
    }

    public function posts() {

        return $this->hasMany('App\Post');
    }

    public function roles() {

        /*
         * Because wie are following convention of naming tables and rows (Table: role_user, Rows: user_id, role_id),
         * wid do not have to provide any additional parameters to belongsToMany method.
         * If wie want to have custom table and rows, then wie must provide that
         * information as parameters in that method. Second parameter is the table name, and the third and fourth
         * parameters are the foreign keys in users and roles tables.
         *
         * Example: return $this->belongsToMany('App\Role', 'user_role', 'userID', 'roleID');
         */
        return $this->belongsToMany('App\Role')->withPivot('created_at');
    }

    public function photos() {

        return $this->morphMany('App\Photo', 'imageable');
    }

}

