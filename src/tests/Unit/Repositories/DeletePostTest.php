<?php

namespace Tests\Unit\Repositories;

use App\Image;
use App\Post;
use App\Repositories\PostRepository;
use App\Tag;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;


class DeletePostTest extends TestCase
{
    use RefreshDatabase;
    protected $PostRepo;

    public function setUp(): void
    {
        parent::setUp();
        $this->PostRepo = app()->make(PostRepository::class);
    }

    public function testDeletePostSuccessReturnTrue() {
        $user = factory(User::class)->create();
        $params = [
            'title' => 'Titleeeee',
            'content' => '$this->faker->paragraph',

            'tags' => [
                '1',
                '2',
            ],
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpeg')
            ]
        ];

        $post = $this->actingAs($user)->PostRepo->create($params);
        $this->actingAs($user)->PostRepo->delete($post->id);
        $this->assertDeleted($post);
        foreach ($post->images() as $img) {
            Storage::assertMissing('public/'.$img->url);
        }
        foreach ($post->tags() as $tag_id) {
            $this->assertDatabaseMissing('post_tag', [
                'post_id' => $post->id,
                'tag_id' => $tag_id
            ]);
        }
    }
}
