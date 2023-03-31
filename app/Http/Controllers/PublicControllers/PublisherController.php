<?php

namespace App\Http\Controllers\PublicControllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index()
    {
        return array_column(Post::select(['by'])->groupBy('by')->get()->toArray(), 'by');
    }
}
