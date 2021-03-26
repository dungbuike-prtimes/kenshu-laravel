<?php

namespace Tests\Unit\Controller;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testUserCanSeeAllPostOfAllUser()
    {
        $request = $this->get('/');

        $request->assertStatus(200);
        $request->assertViewIs('home.index');
    }

    public function testUserCanSeeAllOwnedPost()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('/posts');

        $response->assertViewIs('post.index');
        $response->assertStatus(200);
    }

    public function testUserCanSeeCreatePostForm()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('posts/create');

        $response->assertViewIs('post.create');
        $response->assertStatus(200);
    }

    public function testUserCanCreatePostWithCorrectParams()
    {
        $user = factory(User::class)->make();
        $formData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,

            'tags' => [
                $this->faker->randomDigit,
                $this->faker->randomDigit,
            ],
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpeg')
            ]
        ];

        $response = $this->actingAs($user)->post('/posts', $formData);
        $this->assertDatabaseHas('posts', [
            'title' => $formData['title'],
            'content' => $formData['content']
        ]);
        $response->assertRedirect('/posts');
        $response->assertSessionHas('success');
    }

//    public function correctFromDataForCreatePostProvider(): array
//    {
//        $this->createApplication();
//        return [
//            [
//                'title' => $this->faker->sentence,
//                'content' => $this->faker->paragraph,
//
//                'tags' => [
//                    $this->faker->randomDigit,
//                    $this->faker->randomDigit,
//                ],
//                'images' => [
//                    UploadedFile::fake()->image('image1.jpg'),
//                    UploadedFile::fake()->image('image2.jpeg')
//                ]
//
//            ],
//        ];
//    }


    /**
     * @dataProvider  incorrectFromDataForCreatePostProvider
     * @param $formData
     */
    public function testUserCannotCreatePostWithIncorrectParams($formData) {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->from('/posts/create')->post('/posts', [$formData]);
        $response->assertRedirect('/posts/create');
        $response->assertSessionHasErrorsIn('validations');
    }

    public function incorrectFromDataForCreatePostProvider(): array
    {
        $this->createApplication();
        return [
            [
                'title' => '',
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
                'title' => '$this->faker->paragraph',
                'content' => '',

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
                'title' => '$this->faker->paragraph',
                'content' => '$this->faker->paragraph',
                'tags' => [
                    '1',
                    'akjhsbd',
                ],
                'images' => [
                    UploadedFile::fake()->image('image1.jpg'),
                    UploadedFile::fake()->image('image2.jpeg')
                ]
            ],
            [
                'title' => '$this->faker->paragraph',
                'content' => '$this->faker->paragraph',
                'tags' => [
                    '1',
                    '2',
                ],
                'images' => [
                    UploadedFile::fake()->create('pdf.pdf', 500, 'pdf'),
                    UploadedFile::fake()->image('image2.jpeg')
                ]
            ],

        ];
    }

    public function testUserCanSeePostAsGuest() {
        $post = factory(Post::class)->create();
        $response = $this->get('posts/'.$post->id);
        $response->assertViewIs('post.show');
        $response->assertStatus(200);
        $this->assertGuest();
    }

    public function testUserCanSeeOwnedPostEditForm() {
        $user = factory(User::class)->make();
        $post = factory(Post::class)->create([
            'owner' => $user->id
        ]);

        $response = $this->actingAs($user)->get('posts/'.$post->id.'/edit');
        $response->assertViewIs('post.edit');
        $response->assertStatus(200);
    }

    public function testUserCanUpdatePostWithCorrectParams()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create([
            'owner' => $user->id
        ]);

        $formData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,

            'tags' => [
                $this->faker->randomDigit,
                $this->faker->randomDigit,
            ],
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpeg')
            ]

        ];
        $response = $this->actingAs($user)->from('posts/'.$post->id.'/edit')->put('/posts/'.$post->id, $formData);
        $response->assertRedirect('/posts/'.$post->id);
        $response->assertSessionHas('success');
    }

    public function testUserCanDeleteOwnedPost() {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create([
            'owner' => $user->id
        ]);
        $response = $this->actingAs($user)->from('posts/'.$post->id)->delete('/posts/'.$post->id);
        $response->assertRedirect('/posts');
        $response->assertSessionHas('success');
    }

}
