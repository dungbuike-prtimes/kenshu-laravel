<?php

namespace Tests\Unit\Repositories;

use App\Post;
use App\Repositories\PostRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
class GetPostTest extends TestCase
{
    use RefreshDatabase;
    protected $PostRepo;

    public function setUp(): void
    {
        parent::setUp();
        $this->PostRepo = app()->make(PostRepository::class);
    }

    public function testGetAllPostOfAllUser() {
        $users = factory(User::class, 5)->create()
                    ->each(function ($user) {
                        factory(Post::class, 5)->create(['owner' => $user->id]);
                    });
        $posts = $this->PostRepo->getAll();
        $this->assertCount(25, $posts);
    }

    public function testGetAllPostOfSpecificUser() {
        $user1 = factory(User::class)->create();
        $posts1 = factory(Post::class, 5)->create(['owner' => $user1->id]);
        $user2 = factory(User::class)->create();
        $posts2 = factory(Post::class, 10)->create(['owner' => $user2->id]);
        $posts = $this->PostRepo->getAllByUser($user1->id);
        $this->assertCount(5, $posts);
        $posts = $this->PostRepo->getAllByUser($user2->id);
        $this->assertCount(10, $posts);
    }

    public function testGetSpecificPost() {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['owner' => $user->id]);

        $response_post = $this->PostRepo->getPost($post->id);
        $this->assertNotNull($response_post);
    }
}
