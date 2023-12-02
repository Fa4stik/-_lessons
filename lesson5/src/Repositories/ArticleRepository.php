<?php
namespace my\Repositories;

use my\Exceptions\ArticleIncorrectDataException;
use my\Exceptions\ArticleNotFoundException;
use my\Model\UUID;
use PDO;
use PDOException;
use my\Model\Article;

class ArticleRepository implements PostsRepositoryInterface {

    public function __construct(private PDO $pdo) {
    }

    public function get(UUID $uuid): Article {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE uuid = :uuid");

        try {
            $stmt->execute([
                ":uuid" => $uuid
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new ArticleNotFoundException("Статья с UUID $uuid не найдена");
            }
        } catch (PDOException $e) {
            throw new ArticleIncorrectDataException("Ошибка при получении статьи: " . $e->getMessage());
        }

        return new Article($result['uuid'], $result['author_uuid'],
            $result['title'], $result['text']);
    }

    public function save(Article $article): void {
        $stmt = $this->pdo->prepare("INSERT INTO articles (uuid, author_uuid, title, text) 
            VALUES (:uuid, :author_uuid, :title, :text)");

        try {
            $stmt->execute([
                ':uuid' => $article->getUuid(),
                ':author_uuid' => $article->getAuthorUuid(),
                ':title' => $article->getTitle(),
                ':text' => $article->getText()
            ]);
        } catch (PDOException $e) {
            throw new ArticleIncorrectDataException("Ошибка при сохранении статьи: " . $e->getMessage());
        }
    }
}