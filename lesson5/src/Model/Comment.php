<?php

namespace my\Model;

class Comment {
    function __construct(
        public string $uuid,
        public string $author_uuid,
        public string $article_uuid,
        public string $text) {
    }
}

?>