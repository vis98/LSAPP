@extends('layouts.app')

@section('content')
    <h2></h2>
    <a href="/posts" class="btn btn-info" >GO back</a>
    <p></p>
    <h5>{{$posts['title']}}</h5>
    <p></p>
        <img style="width:100%" src="/storage/images/{{$posts->cover_img}}">
   

    <div>
        {!!$posts['body']!!}

    </div>  
    <hr>
    <small>Written On {{$posts->created_at}}</small>  
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $posts->user_id)
           <a href="/posts/{{$posts['id']}}/edit" class="btn btn-info" >edit</a>
            {!!Form::open(['action'=>['PostsController@destroy',$posts->id],'method'=>'POST','class'=>'pull right'])!!}
            {{Form::hidden('_method','DELETE')}}
            {{Form::submit('DELETE',['class'=>'btn btn-danger'])}}
            {!!Form::close()!!}
        @endif
    @endif    
    
    @endsection
