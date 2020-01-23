<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
Use App\Post;
use DB;
class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function __construct()
    {
        $this->middleware('auth',['except'=>['index','show']]);
    }

    public function index()
    {
      $posts= Post::orderBy('created_at','desc')->paginate(10);
        return view('posts.index')->with('posts',$posts);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required',
            'cover_img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120'
        ]);
        if($request->hasFile('cover_img')){
            $filenameWithExt= $request->file('cover_img')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);
            $extension = $request->file('cover_img')->getClientOriginalExtension();
            $fileNameToStore=$filename.'_'.time().'.'.$extension;
            $path=$request->file('cover_img')->storeAs('/public/images',$fileNameToStore);

        }
        else{
            
            $fileNameToStore='nature.jpg';
    
        }

       $post = new Post;
       $post->title = $request ->input('title');
       $post->body = $request ->input('body');
       $post->user_id= auth()-> user() -> id;
       $post->cover_img =$fileNameToStore;
       $post->save();

       return redirect('/posts')->with('success', 'Post Created!');
    
      
  
  
  

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $posts=Post::find($id);
        return view('posts.show')->with('posts',$posts);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $posts=Post::find($id);
        if(auth()->user()->id !== $posts->user_id){
            return redirect('/posts')->with('error','Unauthorized');
        }
        return view('posts.edit')->with('posts',$posts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required',
        ]);
        if($request->hasfile('cover_img')){
            $filenameWithExt= $request->file('cover_img')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);
            $extension = $request->file('cover_img')->getClientOriginalExtension();
            $fileNameToStore=$filename.'_'.time().'.'.$extension;
            $path=$request->file('cover_img')->storeAs('/public/images',$fileNameToStore);

        }
       $post = Post::find($id);
       $post->title = $request ->input('title');
       $post->body = $request ->input('body');
       if($request->hasFile('cover_img')){
           $post->cover_img= $fileNameToStore;
       }   
       $post->save();


       return redirect('/posts')->with('success', 'Profile Updated!');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error','Unauthoerized');
        }
       
        $post->delete();
        return redirect('/posts')->with('success','Post Deleted');


    }
}
