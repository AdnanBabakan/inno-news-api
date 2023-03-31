<?php

namespace App\Sources;

use App\Models\Post;
use App\Models\Source;
use Illuminate\Support\Facades\Http;

class NewsAPI implements interfaces\SourceInterface
{
    protected $apiUrl = 'https://newsapi.org/v2/top-headlines?country=us&apiKey=';
    protected $apiKey = '1121477b29054192ad381efbaca96fdc';

    public static function getSource()
    {
        return Source::where('handler', self::class)->first();
    }

    public function getPosts()
    {
        return Http::get($this->apiUrl . $this->apiKey)->json()['articles'];
    }

    public function savePosts()
    {
        $array_of_checks = Post::where('source_id', self::getSource()->id)->pluck('check')->toArray();

        $posts = $this->getPosts();
        foreach ($posts as $post) {
            if (in_array(md5($post['url']), $array_of_checks)) continue;

            $new_post = new Post;
            $new_post->source_id = self::getSource()->id;
            $new_post->title = $post['title'];
            $new_post->excerpt = $post['description'];
            $new_post->thumbnail = $post['urlToImage'];
            $new_post->published_at = $post['publishedAt'];
            $new_post->tags = [];
            $new_post->details = [
                'url' => $post['url'],
            ];
            $new_post->check = md5($post['url']);
            $new_post->by = $post['source']['name'] ?? 'NewsAPI';
            $new_post->save();
        }
    }
}
