<?php
namespace my\Repositories;

use PDO;
use PDOException;
use my\Model\Comment;

class CommentRepository implements CommentsRepositoryInterface {

    public function __construct(private PDO $pdo) {
    }

    public function get(string $uuid): Comment {
        $stmt = $this->pdo->prepare("SELECT * FROM comments WHERE uuid = :uuid");
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new \Exception("Статья с UUID $uuid не найдена");
            }

            return new Comment($result['uuid'], $result['author_uuid'],
                $result['article_uuid'], $result['text']);
        } catch (PDOException $e) {
            throw new \Exception("Ошибка при получении статьи: " . $e->getMessage());
        }
    }

    public function save(Comment $comment): void {
        $stmt = $this->pdo->prepare("INSERT INTO comments (uuid, author_uuid, article_uuid, text) 
            VALUES (:uuid, :author_uuid, :article_uuid, :text)");

        try {
            $stmt->execute([
                ':uuid' => $comment->uuid,
                ':author_uuid' => $comment->author_uuid,
                ':article_uuid' => $comment->article_uuid,
                ':text' => $comment->text
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Ошибка при сохранении статьи: " . $e->getMessage());
        }
    }
}