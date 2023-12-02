<?php
namespace my\Repositories;

use my\Model\Comment;
use my\Model\UUID;

interface CommentsRepositoryInterface {
    public function get(UUID $uuid): Comment;
    public function save(Comment $comment): void;
}

?>