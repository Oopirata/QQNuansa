<?php

namespace App\Http\Controllers;

use App\Mail\PostMail;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illiminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', ['post' => $posts]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:5', 'max:255'],
            'content' => ['required', 'min:10'],
            'thumbnail' => ['required', 'image'],
        ]);

        $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails');

        Auth::user()->posts()->create($validated);

        Mail::to('CainChana@test.mail')->send(new PostMail());

        return to_route('posts.index');
    }

    public function show(Post $post)
    {
        return view('posts.show', ['post' => $post]);
    }
    
}
