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

    public function setUp(): void
    {
        parent::setUp();
        $this->PostRepo = app()->make(PostRepository::class);
    }

    /**
     * @dataProvider createPostDataProvider : array
     * @param $params
     */
    public function testUpdatePostSuccess(&$params)
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['owner' => $user->id]);
        $params['id'] = $post->id;
        $image = factory(Image::class)->create(['url' => 'images/img.jpg', 'post_id' => $post->id]);
        $this->assertTrue($post->images->contains($image));

        $file = UploadedFile::fake()->image('img.jpg');
        Storage::putFileAs('public/images', $file, 'img.jpg');
        $params['deleteImage'] = [$image->id];

        $response = $this->actingAs($user)->PostRepo->update($params);
        $this->assertDatabaseHas('posts', [
            'id' => $response->id,
            'title' => $params['title'],
            'content' => $params['content']
        ]);
        if(isset($params['tags'])) {
            foreach ($params['tags'] as $tag_id) {
                $this->assertDatabaseHas('post_tag', [
                    'post_id' => $post->id,
                    'tag_id' => $tag_id
                ]);
            }
        }
        if(isset($params['images'])) {
            foreach ($params['images'] as $img) {
                $this->assertDatabaseHas('images', [
                    'post_id' => $post->id,
                    'url' => 'images/'.$img->hashName()
                ]);
            }
        }
        if(isset($params['deleteImage'])) {
            foreach ($params['deleteImage'] as $d_img) {
                $this->assertDatabaseMissing('images', [
                    'id' => $d_img,
                ]);
            }
        }
    }

    public function createPostDataProvider(): array
    {
        return [
            [
                [
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

                [
                    'id' => 1,
                    'title' => 'Title',
                    'content' => '$this->faker->paragraph',
                    'images' => [
                        UploadedFile::fake()->image('image1.jpg'),
                        UploadedFile::fake()->image('image2.jpeg')
                    ]
                ],

                [
                    'id' => 1,
                    'title' => 'Title',
                    'content' => '$this->faker->paragraph',

                    'tags' => [
                        '1',
                        '2',
                    ],
                ],

                [
                    'id' => 1,
                    'title' => 'Title',
                    'content' => '$this->faker->paragraph',
                ]
            ]
        ];
    }

}
