<?php

namespace UnitTests;

use my\Model\Comment;
use my\Repositories\CommentRepository;
use PHPUnit\Framework\TestCase;
use PDO;

class CommentTests extends TestCase {
    private $repo;

    protected function setUp(): void {
        $pdo = new PDO('sqlite:../db/blog.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->repo = new CommentRepository($pdo);
    }

    public function testSaveComment(): void {
        $comment = new Comment('uuid-2', 'author-uuid-1',
            '1', 'Test Text');
        $this->repo->save($comment);

        $savedComment = $this->repo->get('uuid-2');
        $this->assertEquals($comment, $savedComment);
    }

    public function testFindCommentByUuid(): void {
        $uuid = 'uuid-1';
        $comment = $this->repo->get($uuid);

        $this->assertNotNull($comment);
        $this->assertEquals($uuid, $comment->uuid);
    }

    public function testThrowsExceptionIfCommentNotFound(): void {
        $this->expectException(\Exception::class);

        $nonExistentUuid = 'uuid-non-existent';
        $this->repo->get($nonExistentUuid);
    }
}