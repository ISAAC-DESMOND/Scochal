<?php

namespace App\Http\Controllers;

use App\Models\post;
use App\Events\notifyEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts=[];
        if(post::exists()){
            $posts = post::orderBy('created_at', 'desc')->get();
        }
        return view('dashboard', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->hasFile('image') 
            ? $request->file('image')->store('images', 'public') 
            : null;

        Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'image_url' => $path,
        ]);

        return redirect()->route('post.index')->with('notification', [
            'type' => 'success',
            'message' => 'New post created!',
        ]);
    }

    public function like(Request $request){
        $request->validate([
            'post_id'=>'exists:posts,id',
        ]);

        $post = Post::find($request->post_id);
        $post->increment('like_count');
        event(new notifyEvent( $post->user_id,'New like on your post ','success'));
        return redirect()->route('post.index');

    }

    public function dislike(Request $request){        
        $request->validate([
            'post_id'=>'exists:posts,id',
        ]);

        $post = Post::find($request->post_id);
        $post->increment('dislike_count');
        event(new notifyEvent( $post->user_id,'New dislike on your post ','info'));
        return redirect()->route('post.index');

    }
    
    public function destroy(post $post)
    {
        $post->delete();
    }
}
