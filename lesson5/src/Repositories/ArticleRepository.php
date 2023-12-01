<?php
namespace my\Repositories;

use PDO;
use PDOException;
use my\Model\Article;

class ArticleRepository implements PostsRepositoryInterface {

    public function __construct(private PDO $pdo) {
    }

    public function get(string $uuid): Article {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE uuid = :uuid");
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new \Exception("Статья с UUID $uuid не найдена");
            }

            return new Article($result['uuid'], $result['author_uuid'],
                $result['title'], $result['text']);
        } catch (PDOException $e) {
            throw new \Exception("Ошибка при получении статьи: " . $e->getMessage());
        }
    }

    public function save(Article $article): void {
        $stmt = $this->pdo->prepare("INSERT INTO articles (uuid, author_uuid, title, text) 
            VALUES (:uuid, :author_uuid, :title, :text)");

        try {
            $stmt->execute([
                ':uuid' => $article->uuid,
                ':author_uuid' => $article->author_uuid,
                ':title' => $article->title,
                ':text' => $article->text
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Ошибка при сохранении статьи: " . $e->getMessage());
        }
    }
}