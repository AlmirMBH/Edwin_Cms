@extends('layouts/app')

@section('content')

    <h1>Edit Post</h1>
    <form method="post" action="/posts/{{$post->id}}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <input type="text" name="title" placeholder="Enter title" value="{{$post->title}}">

        <input type="submit" name="submit">
    </form>

    <form method="post" action="/posts/{{ $post->id }}">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">

        <input type="submit" value="DELETE">
    </form>

@stop