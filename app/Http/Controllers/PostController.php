<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Post;
use App\Transformers\PostTransformer;
use Fractal;
use Auth;

class PostController extends Controller
{
    /**
     * Get all current user posts
     *
     * @return array
     */
    public function allOwn()
    {
        $posts = Post::where('user_id', Auth::user()->id)->get();

        return Fractal::collection($posts)
            ->transformWith(new PostTransformer())
            ->toArray();
    }

    public function getDetail(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->first();

        return Fractal::item($post)
            ->transformWith(new PostTransformer())
            ->toArray();
    }

    /**
     * Add new post
     *
     * @param Request $request
     *
     * @return array
     */
    public function add(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
        ]);

        $post = Post::create([
            'title'   => $request->title,
            'content' => $request->content,
            'user_id' => Auth::user()->id,
        ]);

        return Fractal::item($post)
            ->transformWith(new PostTransformer())
            ->respond(201);
    }

    /**
     * Update post
     *
     * @param int $id
     *
     * @return array
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
        ]);

        $post = Post::findOrFail($id);

        $post->title   = $request->title;
        $post->content = $request->content;

        $post->save();

        return Fractal::item($post)
            ->transformWith(new PostTransformer())
            ->toArray();
    }

    /**
     * Delete post
     *
     * @param int $id
     *
     * @return ...
     */
    public function delete(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response(NULL, 200);
    }
}
