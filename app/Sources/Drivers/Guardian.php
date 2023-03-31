<?php

namespace App\Sources\Drivers;

use App\Models\Post;
use App\Models\Source;
use App\Sources\interfaces\SourceInterface;
use Illuminate\Support\Facades\Http;

class Guardian implements SourceInterface
{
    protected $apiUrl = 'https://content.guardianapis.com/search?show-fields=thumbnail,trailText&api-key=';
    protected $apiKey = '5870fb90-f83c-4933-8002-4cdda0ba0a82';

    public static function getSource()
    {
        return Source::where('handler', self::class)->first();
    }

    public function getPosts()
    {
        return Http::get($this->apiUrl . $this->apiKey)->json()['response']['results'];
    }

    public function savePosts()
    {
        $array_of_checks = Post::where('source_id', self::getSource()->id)->pluck('check')->toArray();

        $posts = $this->getPosts();
        foreach ($posts as $post) {
            if (in_array(md5($post['id']), $array_of_checks)) continue;

            $new_post = new Post;
            $new_post->source_id = self::getSource()->id;
            $new_post->title = $post['webTitle'];
            $new_post->excerpt = $post['fields']['trailText'];
            $new_post->thumbnail = $post['fields']['thumbnail'];
            $new_post->published_at = $post['webPublicationDate'];
            $new_post->tags = [];
            $new_post->details = [
                'url' => $post['webUrl'],
            ];
            $new_post->check = md5($post['id']);
            $new_post->by = 'The Guardian';
            $new_post->save();
        }
    }
}
