<?php

namespace App\Http\Controllers\PrivateControllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->feed;
    }

    public function posts(Request $request)
    {
        $posts_query = (new Post)->newQuery();

        $settings = Collection::make($request->user()->feed->settings);

        if($settings->has('by')) {
            $by_split = explode(',', $settings->get('by'));
            $posts_query->whereIn('by', $by_split);
        }

        if ($request->has('q')) {
            $posts_query->where('title', 'like', '%' . $request->q . '%')
                ->orWhere('excerpt', 'like', '%' . $request->q . '%')
                ->orWhere('by', 'like', '%' . $request->q . '%');
        }

        if ($request->has('after')) {
            $posts_query->where('published_at', '>=', Carbon::parse($request->after));
        }

        if ($request->has('before')) {
            $posts_query->where('published_at', '<=', Carbon::parse($request->before));
        }

        $posts_query->orderBy('published_at', 'desc');

        return $posts_query->paginate(10);
    }

    public function update(Request $request)
    {

    }
}
