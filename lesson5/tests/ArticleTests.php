<?php

namespace UnitTests;

use PHPUnit\Framework\TestCase;
use my\Repositories\ArticleRepository;
use my\Model\Article;
use PDO;

class ArticleTests extends TestCase {
    private $repo;

    protected function setUp(): void {
        $pdo = new PDO('sqlite:../db/blog.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->repo = new ArticleRepository($pdo);
    }

    public function testSaveArticle(): void {
        $article = new Article('uuid-1', 'author-uuid-1',
            'Test Title', 'Test Text');
        $this->repo->save($article);

        $savedArticle = $this->repo->get('uuid-1');
        $this->assertEquals($article, $savedArticle);
    }

    public function testFindArticleByUuid(): void {
        $uuid = 'uuid-1';
        $article = $this->repo->get($uuid);

        $this->assertNotNull($article);
        $this->assertEquals($uuid, $article->uuid);
    }

    public function testThrowsExceptionIfArticleNotFound(): void {
        $this->expectException(\Exception::class);

        $nonExistentUuid = 'uuid-non-existent';
        $this->repo->get($nonExistentUuid);
    }
}