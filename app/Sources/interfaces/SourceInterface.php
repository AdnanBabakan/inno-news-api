<?php

namespace App\Sources\interfaces;

interface SourceInterface
{
    public static function getSource();
    public function getPosts();
    public function savePosts();
}
