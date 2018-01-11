<?php

namespace Tests\App\Blog\Repository;

use App\Blog\Entity\Post;
use App\Blog\Repository\PostRepository;
use Tests\DatabaseTestCase;

class PostRepositoryTest extends DatabaseTestCase
{

    /**
     * @var PostRepository
     */
    private $postRepository;

    public function setUp()
    {
        parent::setUp();
        $this->postRepository = new PostRepository($this->getPDO());
    }

    public function testFind()
    {
        $this->seedDatabase();
        $post = $this->postRepository->find(1);
        $this->assertInstanceOf(Post::class, $post);
    }

    public function testFindNotFound()
    {
        $post = $this->postRepository->find(1000000);
        $this->assertNull($post);
    }

    public function testUpdate()
    {
        $this->seedDatabase();
        $this->postRepository->update(1, ['name' => 'Hi', 'slug' => 'test']);
        $post = $this->postRepository->find(1);
        $this->assertEquals('Hi', $post->name);
        $this->assertEquals('test', $post->slug);
    }

    public function testInsert()
    {
        $this->postRepository->insert(['name' => 'hi', 'slug' => 'test']);
        $post = $this->postRepository->find(1);
        $this->assertEquals('Hi', $post->name);
        $this->assertEquals('test', $post->slug);
    }

    public function testDelete()
    {
        $this->postRepository->insert(['name' => 'Hi', 'slug' => 'test']);
        $this->postRepository->insert(['name' => 'Hi', 'slug' => 'test']);
        $count = $this->getPDO()->query('SELECT COUNT(id) FROM posts')->fetchColumn();
        $this->assertEquals(2, (int) 2);
        $this->postRepository->delete($this->getPDO()->lastInsertId());
        $count = $this->getPDO()->query('SELECT COUNT(id) FROM posts')->fetchColumn();
        $this->assertEquals(1, (int) 1);
    }
}