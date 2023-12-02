<?php

namespace UnitTests\Model;

use my\Model\Article;
use my\Model\UUID;
use PHPUnit\Framework\TestCase;

class ArticleTests extends TestCase
{
    public function testGetData(): void {
        $uuid = UUID::random();
        $authorUuid = UUID::random();
        $title = 'Title1';
        $text = 'Text';
        $article = new Article(
            $uuid,
            $authorUuid,
            $title,
            $text
        );

        $this->assertEquals($uuid, $article->getUuid());
        $this->assertEquals($authorUuid, $article->getAuthorUuid());
        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals($text, $article->getText());
    }
}