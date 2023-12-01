<?php

namespace my\Repositories;

use my\Model\Article;

interface PostsRepositoryInterface {
    public function get(string $uuid): Article;
    public function save(Article $article): void;
}
?>