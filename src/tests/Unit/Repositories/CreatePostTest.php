<?php

namespace Tests\Unit\Repositories;

use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryInterface;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Mockery;

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
    public function testCreatePostSuccessReturnTrue($params)
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->PostRepo->create($params);
        $this->assertTrue($response);
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

