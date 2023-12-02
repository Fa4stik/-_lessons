<?php

namespace my\Model;

use my\Model\UUID;

class Comment {
    public function __construct(
        private UUID $uuid,
        private UUID $authorUuid,
        private UUID $articleUuid,
        private string $text) {
    }

    public function getUuid(): UUID {
        return $this->uuid;
    }

    public function getAuthorUuid(): UUID {
        return $this->authorUuid;
    }

    public function getArticleUuid(): UUID {
        return $this->articleUuid;
    }

    public function getText(): string {
        return $this->text;
    }
}

?>