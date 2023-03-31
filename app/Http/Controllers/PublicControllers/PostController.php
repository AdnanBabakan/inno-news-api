<?php

namespace App\Http\Controllers\PublicControllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts_query = (new Post)->newQuery();

        if($request->has('by')) {
            $posts_query->where('by', $request->by);
        }

        if($request->has('q')) {
            $posts_query->where('title', 'like', '%' . $request->q . '%')
            ->orWhere('excerpt', 'like', '%' . $request->q . '%')
            ->orWhere('by', 'like', '%' . $request->q . '%');
        }

        if($request->has('after')) {
            $posts_query->where('published_at', '>', $request->after);
        }

        if($request->has('before')) {
            $posts_query->where('published_at', '<', $request->before);
        }

        return $posts_query->paginate(10);
    }
}
