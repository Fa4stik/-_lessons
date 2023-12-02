<?php

namespace my\Model;

use my\Model\UUID;

class Article {
    public function __construct(
        private UUID $uuid,
        private UUID $authorUuid,
        private string $title,
        private string $text) {
    }

    public function getUuid(): UUID {
        return $this->uuid;
    }

    public function getAuthorUuid(): UUID {
        return $this->authorUuid;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getText(): string {
        return $this->text;
    }
}