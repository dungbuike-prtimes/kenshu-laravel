<?php

namespace Tests\Unit\Repositories;

use App\Image;
use App\Post;
use App\Repositories\PostRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdatePostTest extends TestCase
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
    public function testUpdatePostSuccessReturnTrue(&$params)
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['owner' => $user->id]);
        $params['id'] = $post->id;

        $response = $this->actingAs($user)->PostRepo->update($params);
        $this->assertTrue($response);
    }

    public function createPostDataProvider(): array
    {
        return [
            [
                0 => [
                    'id' => 1,
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
                    'id' => 1,
                    'title' => 'Title',
                    'content' => '$this->faker->paragraph',
                    'images' => [
                        UploadedFile::fake()->image('image1.jpg'),
                        UploadedFile::fake()->image('image2.jpeg')
                    ]
                ],

                2 => [
                    'id' => 1,
                    'title' => 'Title',
                    'content' => '$this->faker->paragraph',

                    'tags' => [
                        '1',
                        '2',
                    ],
                ],

                3 => [
                    'id' => 1,
                    'title' => 'Title',
                    'content' => '$this->faker->paragraph',
                ]
            ]];
    }

}
