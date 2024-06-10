<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicPostsResource;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Date;

class PublicPostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::select('id', 'title', 'published')->get();

        return response()->json(['posts' => $posts]);
    }

    public function handlePublic($id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                throw new \Exception('Post not found');
            }
            $post->published = !$post->published;
            $post->published_at = Date::now();
            $post->save();
            
            return new PublicPostsResource($post); 
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404); 
        }
    }
}
