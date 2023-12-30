<?php

namespace tests\Repositories;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use src\Exceptions\CommentNotFoundException;
use src\Model\Comment;
use src\Model\UUID;
use src\Repositories\CommentRepository;

class CommentRepositoryTests extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private $repo;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->repo = new CommentRepository($this->pdoMock);
    }

    public function testSaveComment(): void {
        $uuid = UUID::random();
        $authorUuid = UUID::random();
        $articleUuid = UUID::random();
        $text = 'Test Text';
        $comment = new Comment($uuid, $authorUuid, $articleUuid, $text);

        $expectedParams = [
            ':uuid' => $uuid,
            ':author_uuid' => $authorUuid,
            ':article_uuid' => $articleUuid,
            ':text' => $text
        ];

        $this->pdoMock->method('prepare')
            ->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedParams));

        $this->repo->save($comment);
    }

    public function testFindCommentByUuid(): void {
        $uuid = UUID::random();
        $authorUuid = UUID::random();
        $articleUuid = UUID::random();
        $text = 'Test Text';

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn([
            'uuid' => $uuid,
            'author_uuid' => $authorUuid,
            'article_uuid' => $articleUuid,
            'text' => $text
        ]);

        $comment = $this->repo->get($uuid);

        $this->assertNotNull($comment);
        $this->assertEquals($uuid, $comment->getUuid());
    }

    public function testThrowsExceptionIfCommentNotFound(): void {
        $nonExistentUuid = UUID::random();

        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage("Комментарий с UUID $nonExistentUuid не найден");

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn(false);

        $this->repo->get($nonExistentUuid);
    }
}