<?php

namespace Tests\Unit;

use App\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

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

    /**
     * @dataProvider correctParamsForCreatePostProvider
     */
    public function testUserCanCreatePostWithCorrectParams($formData)
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->post('/posts', [$formData]);
        $response->assertRedirect('/posts');
        $response->assertSessionHas('success');
    }

    public function correctParamsForCreatePostProvider(): array
    {
        $this->refreshApplication();
        return [
            [
                'title' => 'aaaaaaaaaaaaa',
                'content' => $this->faker->paragraph,

                'tags' => [
                    $this->faker->randomDigit,
                    $this->faker->randomDigit,
                ],
            ],
            [
                'title' => $this->faker->sentence,
                'content' => $this->faker->paragraph,
                'images' => [
                    UploadedFile::fake()->image('image1.jpg'),
                    UploadedFile::fake()->image('image2.jpeg')
                ]
            ],
            [
                'title' => $this->faker->sentence,
                'content' => $this->faker->paragraph,
            ],

            [
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
        ]
        ];
    }
}
