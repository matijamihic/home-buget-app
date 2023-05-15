<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    // test login
    public function test_login(): void
    {
        // create user in database using factory
        $user = User::factory()->create();

        // add this to try block
        try {
            // login user
            $response = $this->post('/api/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

            // assert that the user was authenticated
            $this->assertAuthenticated();

            // assert user has received authorization information in response
            $response->assertJsonStructure(['authorisation']);

            $user->delete();
        } catch (\Throwable $th) {
            // remove user from database
            $user->delete();
            throw $th;
        }
    }

    //test logout
    public function test_logout(): void
    {
        // create user in database using factory
        $user = User::factory()->create();

        try {
            // login user
            $response = $this->post('/api/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

            // save token from response
            $token = $response->json('authorisation.token');

            // logout user using token
            $response = $this->post('/api/logout')->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ]);

            $this->assertGuest();

            $user->delete();
        } catch (\Throwable $th) {
            // remove user from database
            $user->delete();
            throw $th;
        }
    }
}
