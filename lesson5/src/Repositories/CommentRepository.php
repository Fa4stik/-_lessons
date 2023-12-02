<?php
namespace my\Repositories;

use my\Exceptions\CommentIncorrectDataException;
use my\Exceptions\CommentNotFoundException;
use my\Model\UUID;
use PDO;
use PDOException;
use my\Model\Comment;

class CommentRepository implements CommentsRepositoryInterface {
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function get(UUID $uuid): Comment {
        $stmt = $this->pdo->prepare("SELECT * FROM comments WHERE uuid = :uuid");

        try {
            $stmt->execute([
                ":uuid" => $uuid
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new CommentNotFoundException("Комментарий с UUID $uuid не найден");
            }
        } catch (PDOException $e) {
            throw new CommentIncorrectDataException("Ошибка при получении комментария: " . $e->getMessage());
        }

        return new Comment($result['uuid'], $result['author_uuid'],
            $result['article_uuid'], $result['text']);
    }

    public function save(Comment $comment): void {
        $stmt = $this->pdo->prepare("INSERT INTO comments (uuid, author_uuid, article_uuid, text) 
            VALUES (:uuid, :author_uuid, :article_uuid, :text)");

        try {
            $stmt->execute([
                ':uuid' => $comment->getUuid(),
                ':author_uuid' => $comment->getAuthorUuid(),
                ':article_uuid' => $comment->getArticleUuid(),
                ':text' => $comment->getText()
            ]);
        } catch (PDOException $e) {
            throw new CommentIncorrectDataException("Ошибка при сохранении комментария: " . $e->getMessage());
        }
    }
}