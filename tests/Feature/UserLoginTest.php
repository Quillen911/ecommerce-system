<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'username' => 'ismail',
            'email' => 'hecksoft0@gmail.com',
            'password' => bcrypt('ismail')
        ]);
        $response = $this->post('/postlogin', [
            'email' => $user->email,
            'password' => 'ismail'
        ]);
       // $response->assertStatus(302);
        $response->assertRedirect('/main');
    }
}