<?php

namespace App\Sources;

use App\Models\Source;

class SourceHandler
{
    public static function runNewsSources()
    {
        $news_sources = Source::get();
        foreach ($news_sources as $news_source) {
            if (class_exists($news_source->handler)) {
                $handler = new $news_source->handler;
                $handler->savePosts();
            }
        }
    }
}
