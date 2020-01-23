@extends('layouts.app')

@section('content')
    <div>
        <h3> </h3>
        <h5>Posts</h5>
    </div>
    @if(count($posts) > 0)
        @foreach($posts as $post)
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                        <img style="width:100%" src="/storage/images/{{$post->cover_img}}">
                    </div>
                <div class="col-md-8 col-sm-8">
                    <h4><a href="/posts/{{$post->id}}">{{$post->title}}</a></h4>
                    <p><a>{{$post->created_at}} by </a></p>
                </div>
            </div>    
        </div>
        @endforeach

          {{$posts->links()}}
    @else
        <p>No Post</p>
    @endif        
@endsection