<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Models\View;
use Carbon\Carbon;

class PostController extends Controller
{
    public function index(Request $request)
    {

        $search_keyword  = $request->input('search');
        $search_category = $request->input('category');

        $posts = Post::orderBy('id', 'DESC')->where('published', 1);
        
        if ($search_category) {
            $posts->where('category_id', $search_category);
        } else {
            $posts->where('title', 'like', "%$search_keyword%");
        }

        $posts               = $posts->paginate(6);
        $categories          = $this->getCategory();
        $unique_category_ids = Post::select('category_id')
            ->distinct()
            ->get();
        $latest_posts = Post::orderBy('id', 'DESC')
            ->select('id', 'title', 'slug', 'image', 'updated_at')
            ->limit(3)
            ->get();

        return response()->json([
            'posts'             => $posts,
            'categories'        => $categories,
            'uniqueCategoryIds' => $unique_category_ids,
            'latestPosts'       => $latest_posts
        ]);
    }

    public function getCategory()
    {
        $categories = Category::all();

        return CategoryResource::collection($categories);
    }

    public function show(Request $request)
    {
        try {
            $slug     = $request->slug;
            $post     = Post::where('slug', $slug)->first();
            $comments = $post->comments;


            $view = View::where('post_id', $post->id)
                ->where('created_at', Carbon::today())
                ->first();
            if (!$view) {
                $view = new View();
                $view->post_id = $post->id;
                $view->views   = 0;
                $view->save();
            }
            $view->views += 1;
            $view->save();

            return response()->json([
                'post'     => new PostResource($post),
                'author'   => new AuthorResource($post->author),
                'comments' => $comments,
                'views'    => $view->views
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
