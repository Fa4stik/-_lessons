<?php

namespace my\Repositories;

use my\Model\Article;
use my\Model\UUID;

interface PostsRepositoryInterface {
    public function get(UUID $uuid): Article;
    public function save(Article $article): void;
}
?>