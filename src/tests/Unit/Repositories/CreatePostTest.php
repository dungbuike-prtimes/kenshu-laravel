<?php

namespace Tests\Unit\Repositories;

use App\Post;
use App\Repositories\PostRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var mixed
     */
    protected $PostRepo;
    protected $auth;

    public function setUp(): void
    {
        parent::setUp();
        $this->PostRepo = app()->make(PostRepository::class);
    }

    /**
     * @dataProvider createPostDataProvider : array
     * @param $params
     */
    public function testCreatePostSuccess($params)
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->PostRepo->create($params);
        $this->assertDatabaseHas('posts', ['id' => $response->id]);
        if (isset($params['images'])) {
            Storage::assertExists(Storage::files('public/images'.$params['images'][0]->hashName()));
            foreach ($params['images'] as $img) {
                Storage::assertExists(Storage::files('public/images'.$params['images'][0]->hashName()));
                $this->assertDatabaseHas('images', [
                    'post_id' => $response->id,
                    'url' => 'images/'.$img->hashName()
                ]);
            }
        }
        if(isset($params['tags'])) {
            foreach ($params['tags'] as $tag_id) {
                $this->assertDatabaseHas('post_tag', [
                    'post_id' => $response->id,
                    'tag_id' => $tag_id
                ]);
            }
        }
    }

    public function createPostDataProvider(): array
    {
        return [
            [
            0 => [
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
            ],

            1 => [
                'title' => 'Title',
                'content' => '$this->faker->paragraph',
                'images' => [
                    UploadedFile::fake()->image('image1.jpg'),
                    UploadedFile::fake()->image('image2.jpeg')
                ]
            ],

            2 => [
                'title' => 'Title',
                'content' => '$this->faker->paragraph',

                'tags' => [
                    '1',
                    '2',
                ],
            ],

            3 => [
                'title' => 'Title',
                'content' => '$this->faker->paragraph',
            ]
        ]];
    }

}

