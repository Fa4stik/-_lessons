<?php

namespace tests\Repositories;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use src\Exceptions\PostNotFoundException;
use src\Model\Post;
use src\Model\UUID;
use src\Repositories\PostRepository;

class ArticleRepositoryTests extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private $repo;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->repo = new PostRepository($this->pdoMock);
    }

    public function testSaveArticle(): void {
        $uuid = UUID::random();
        $authorUuid = UUID::random();
        $article = new Post($uuid, $authorUuid, 'Test Title', 'Test Text');

        $expectedParams = [
            ':uuid' => $uuid,
            ':author_uuid' => $authorUuid,
            ':title' => 'Test Title',
            ':text' => 'Test Text'
        ];

        $this->pdoMock->method('prepare')
            ->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedParams));

        $this->repo->save($article);
    }

    public function testFindArticleByUuid(): void {
        $uuid = UUID::random();
        $authorUuid = UUID::random();

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn([
            'uuid' => $uuid,
            'author_uuid' => $authorUuid,
            'title' => 'Test Title',
            'text' => 'Test Text'
        ]);

        $article = $this->repo->get($uuid);

        $this->assertNotNull($article);
        $this->assertEquals($uuid, $article->getUuid());
    }

    public function testThrowsExceptionIfArticleNotFound(): void {
        $nonExistentUuid = UUID::random();

        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage("Статья с UUID $nonExistentUuid не найдена");

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn(false);

        $this->repo->get($nonExistentUuid);
    }
}