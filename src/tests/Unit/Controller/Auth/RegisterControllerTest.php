<?php

namespace Tests\Unit\Controller\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Condition: request to Show login form
     * result
     */
    public function testUserCanSeeRegisterForm()
    {
        $response = $this->get('/register');

        $response->assertViewIs('auth.register');
    }

    /**
     *
     */
    public function testUserCannotSeeRegisterFormWhenAuthenticated()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect('/');
    }

    /**
     * @dataProvider registerFormDataProvider
     */
    public function testUserCannotCreateAccountWithInCorrectValidationInput($formData)
    {
        $response = $this->from('/register')->post('/register', [$formData]);
        $response->assertSessionHasErrorsIn('validations');
        $response->assertRedirect('/register');
    }

    public function registerFormDataProvider(): array
    {
        return [
            [
                'email' => 'asdfsdfsdf',
                'name' => 'Name',
                'phone_number' => '123456789',
                'password' => '123456',
                'password_confirmation' => '123456'
            ],

            [
                'email' => 'test-email!12#"@email.com',
                'name' => 'Name',
                'phone_number' => '123456789',
                'password' => '123456',
                'password_confirmation' => '123456'
            ],

            [
                'email' => 'test.email@email.com',
                'name' => 'Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name Name ',
                'phone_number' => '123456789',
                'password' => '123456',
                'password_confirmation' => '123456'
            ],

            [
                'email' => 'test.email@email.com',
                'name' => 'Name',
                'phone_number' => '123456789124523456782',
                'password' => '123456',
                'password_confirmation' => '123456'
            ],

            [
                'email' => '',
                'name' => 'Name',
                'phone_number' => '123456789',
                'password' => '123456',
                'password_confirmation' => '1234567890'
            ],


            [
                'email' => 'test.email@email.com',
                'name' => '',
                'phone_number' => '123456789',
                'password' => '123456',
                'password_confirmation' => '1234567890'
            ],


            [
                'email' => 'test.email@email.com',
                'name' => 'Name',
                'phone_number' => '',
                'password' => '123456',
                'password_confirmation' => '1234567890'
            ],


            [
                'email' => 'test.email@email.com',
                'name' => 'Name',
                'phone_number' => '123456789',
                'password' => '',
                'password_confirmation' => '1234567890'
            ],


            [
                'email' => 'test.email@email.com',
                'name' => 'Name',
                'phone_number' => '123456789',
                'password' => '123456',
                'password_confirmation' => ''
            ],

        ];
    }

    public function testUserCanCreateAccountWithCorrectInput()
    {
        $response = $this->from('/register')->post('/register', [
            'email' => 'dungbk474@prtimes.co.jp',
            'name' => 'Name',
            'phone_number' => '012345678',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertSessionMissing('error');
        $response->assertSessionHasNoErrors('validations');
        $response->assertRedirect('/login');
    }
}
