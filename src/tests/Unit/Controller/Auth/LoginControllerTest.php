<?php

namespace Tests\Unit\Controller\Auth;


use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanViewLoginForm() {
        $response = $this->get('/login');

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function testUserCannotViewLoginWhenAuthenticated() {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/');
    }

    public function testUserCanLoginWithCorrectCredentials() {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = '123456'),
        ]);

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function testUserCannotLoginWithIncorrectPassword() {
        $user = factory(User::class)->create([
            'password' => Hash::make('123456'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'incorrect-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function testUserCannotLoginWithIncorrectEmail() {
        $user = factory(User::class)->create([
            'password' => Hash::make('123456'),
            'phone_number' => '12323214123',
            'email' => 'testemail@email.com'
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'incorrect-email@email.com',
            'password' => '123456',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function testLogoutWhenAuthenticatedReturnTrue() {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user);

        $response = $this->get('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
