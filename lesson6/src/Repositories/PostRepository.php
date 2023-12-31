<?php
namespace src\Repositories;

use PDO;
use PDOException;
use src\Exceptions\PostIncorrectDataException;
use src\Exceptions\PostNotFoundException;
use src\Model\Post;
use src\Model\UUID;

class PostRepository implements PostsRepositoryInterface {

    public function __construct(private PDO $pdo) {
    }

    public function get(UUID $uuid): Post
    {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE uuid = :uuid");

        try {
            $stmt->execute([
                ":uuid" => $uuid
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new PostNotFoundException("Статья с UUID $uuid не найдена");
            }
        } catch (PDOException $e) {
            throw new PostIncorrectDataException("Ошибка при получении статьи: " . $e->getMessage());
        }

        return new Post($result['uuid'], $result['author_uuid'],
            $result['title'], $result['text']);
    }

    public function save(Post $post): void {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $post->getAuthorUuid()]);
        if ($stmt->fetchColumn() == 0) {
            throw new PostIncorrectDataException("Автор с UUID {$post->getAuthorUuid()} не найден");
        }

        $stmt = $this->pdo->prepare("INSERT INTO posts (uuid, author_uuid, title, text) 
            VALUES (:uuid, :author_uuid, :title, :text)");

        try {
            $stmt->execute([
                ':uuid' => $post->getUuid(),
                ':author_uuid' => $post->getAuthorUuid(),
                ':title' => $post->getTitle(),
                ':text' => $post->getText()
            ]);
        } catch (PDOException $e) {
            throw new PostIncorrectDataException("Error when save post: " . $e->getMessage());
        }
    }

    public function delete(string $uuid): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);

        if ($stmt->rowCount() === 0) {
            throw new PostNotFoundException("Post with UUID $uuid not found");
        }
    }
}