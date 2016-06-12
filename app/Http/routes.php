<?php

use App\Photo;
use App\Video;
use App\Tag;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


//Route::get('/post/{id}', 'PostsController@index');

Route::resource('/posts', 'PostsController');

//send data to the view
Route::get('/contact', 'PostsController@show_my_view');
Route::get('/post/{id}/{name}/{pass}', 'PostsController@show_post');


//Raw database SQL Queries
Route::get('/insert', function() {
    DB::insert('insert into posts(title, content) values(?, ?)', ['PHP with Laravel', 'Laravel is the best framework for web Apps']);
});

Route::get('/read', function() {
    $results = DB::select('select * from posts where id = ?', [1]);

    $results->id; //get the properties from the object
    return $results; //return a object with properties, rows in a table are properties in results
});

Route::get('/update', function() {
    $updated = DB::update('update posts set title = "Updated post" where id = ?', [1]);

    return $updated;
});

Route::get('/delete', function() {
    $delete = DB::delete('delete from posts where id = ?', [1]);

    return $delete;
});

/*

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function() {
    return "This is About page!";
});


//get data from url
Route::get('/post/{id}', function($id) {
    return "This is post number: " . $id;
});


//naming routes
Route::get('admin/posts/example', array('as' => 'admin.home', function() {
    $url = route('admin.home');

    return "This is: " . $url;
}));
*/


/**
 |----------------------------------------
 | ELOQUENT
 |----------------------------------------
 */

use App\Post;

//read data with eloquent
Route::get('/find', function() {
    $posts = Post::all(); //get all data in the table

    $post = Post::find(2); //find specific row
    echo $post->title;

/*    foreach ($posts as $post) {
        echo $post->title;
    }*/
});

Route::get('/findwhere', function() {
    $posts = Post::where('id', 2)->orderBy('id', 'desc')->take(1)->get();

    foreach ($posts as $post) {
        echo $post->title;
    }

});

Route::get('/findmore', function() {
    //$posts = Post::findOrFail(2);

    $posts = Post::where('users_count', '<', 50)->firstOrFail(); //conditions, find first that match provided condition

    return $posts;
});


//basic insert with eloquent
Route::get('/basicinsert', function() {
    $post = new Post; //new Eloquent model

    $post->title = "New Eloquent title";
    $post->content = "Eloquent is very nice. How much?";

    $post->save(); //save the record to the database
});

//create method with eloquent
Route::get('/create', function() {
    Post::create(['title'=>'The create method', 'content'=>'Test for create method with eloquent']);
});

//update method
Route::get('/update2', function() {
    Post::where('id', 2)->where('is_admin', 0)->update(['title'=>'New PHP Title', 'content'=>'This is new content']);
});

//delete method
Route::get('/delete2', function() {
    $post = Post::find(7);
    $post->delete();
});

//destroy method
Route::get('destroy', function() {
    Post::destroy([4,5,6]); //delete multiple rows

    //Post::where('is_admin', 0)->delete();
});

//soft delete
Route::get('/softdelete', function() {
    Post::find(7)->delete();
});

//read only trashed items
Route::get('/readsoftdelete', function() {
    $post = Post::onlyTrashed()->where('is_admin', 0)->get();  //get only trashed items
    //$post = Post::withTrashed()->where('is_admin', 0)->get(); //get all items

    return $post;
});

//restore trashed item
Route::get('/restore', function() {
    Post::withTrashed()->where('is_admin', 0)->restore();
});

//delete permanently
Route::get('/forcedelete', function() {
    Post::onlyTrashed()->where('id', 8)->forceDelete();
});

/**
|----------------------------------------
| ELOQUENT Relationships
|----------------------------------------
 */

use App\User;

//one to one relationship - get post
Route::get('/user/{id}/post', function($id) {
    return User::find($id)->post->title;
});

//inverse relation - get user
Route::get('/post/{id}/user', function($id) {
    return Post::find($id)->user->name;
});

//one to many relationship
Route::get('/posts', function() {
    $user = User::find(1);

    foreach($user->posts as $post) {
        echo $post->title . "<br>";
    }
});

//many to many relationship
Route::get('/user/{id}/role', function($id) {

    //return user role row from database
    $user = User::find($id)->roles()->orderBy('id', 'desc')->get();
    return $user;

    //return user role
    /*$user = User::find($id);
    foreach($user->roles as $role) {
        return $role->name;
    }*/
});

//Accessing the intermediate table / pivot
Route::get('/user/pivot', function() {
    $user = User::find(1);

    foreach($user->roles as $role) {
        echo $role->pivot->created_at;
    }
});

use App\Country;

//Has many through relation
Route::get('/user/country', function() {
    $county = Country::find(2);

    foreach($county->posts as $post) {

        return $post->title;
    }
});


/*
 * Polymorphic Relationship
 */

//Get user photos
Route::get('/user/photos', function() {

    $user = User::find(1);

    foreach ($user->photos as $photo) {

        return $photo;
    }
});

//Get post photos
Route::get('/post/photos', function() {

    $post = Post::find(1);

    foreach ($post->photos as $photo) {

        return $photo;
    }
});

//Polymorphic relation - inverse
Route::get('/photo/{id}/post', function($id) {

    $photo = Photo::findOrFail($id);

    return $photo->imageable;
});

//Polymorphic relation Many to Many
//Get tags associated with the selected post
Route::get('post/tags', function() {

    $post = Post::find(1);

    foreach ($post->tags as $tag) {
        echo $tag->name . "<br>";
    }
});

//Get tags associated with the selected video
Route::get('video/tags', function() {
    
    $video = Video::find(1);

    foreach ($video->tags as $tag) {

        echo $tag->name . "<br>";
    }
});

//Polymorphic relation Many to Many - inverse
//Get the posts that have selected tag
Route::get('postowner/tag', function() {

    $tag = Tag::find(2);
    
    foreach ($tag->posts as $post) {
        
        echo $post->title . "<br>";
    }
});

//Get the video that have selected tag
Route::get('videoowner/tag', function() {

    $tag = Tag::find(1);

    foreach ($tag->videos as $video) {

        echo $video->name . "<br>";
    }
});

//attach method attach a role to the user, even if that role already exists for that user
Route::get('/attach', function() {

    $user = User::findOrFail(1);

    $user->roles()->attach(2);
});

//detach method detach the role for specified user
Route::get('/detach', function() {

    $user = User::findOrFail(1);

    $user->roles()->detach(2);
});


//sync method are used for adding a specified roles to the user, and removing any other role that had previous been assigned
Route::get('/sync', function() {

    $user = User::findOrFail(2);

    $user->roles()->sync([1]);
});


/*
|--------------------------------------------------------------------------
| CRUD APP
|--------------------------------------------------------------------------
*/

Route::resource('/posts', 'PostsController');