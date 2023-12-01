<?php

namespace my\Model;

class Article {
    function __construct(
        public string $uuid,
        public string $author_uuid,
        public string $title,
        public string $text) {
    }
}

?>