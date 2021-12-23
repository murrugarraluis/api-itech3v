<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function login()
    {
        User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $json = [
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => '123456',
        ];

        $response = $this->postJson("api/login", $json);
        $token = $response->baseResponse->original['token'];
        $header = [
            "Authorization" => "Bearer " . $token
        ];
        return $header;
    }

    public function test_store()
    {
        $this->withoutExceptionHandling();
        User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $json = [
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => '123456',
        ];

        $response = $this->postJson("api/login", $json);
        $token = $response->baseResponse->original['token'];
        $header = [
            "Authorization" => "Bearer " . $token
        ];
        $json = [
            'name' => 'Luis',
            'email' => 'luis@gmail.com',
            'password' => '123456',
        ];
        $this->postJson("api/register", $json, $header)
            ->assertStatus(201)
            ->assertJson(['message' => 'Usuario Registrado']);
    }
    public function test_store_validate_data_unique()
    {
        User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $json = [
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => '123456',
        ];

        $response = $this->postJson("api/login", $json);
        $token = $response->baseResponse->original['token'];
        $header = [
            "Authorization" => "Bearer " . $token
        ];
        $json = [
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => '123456',
        ];
        $this->postJson("api/register", $json, $header)
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_login()
    {
        User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $json = [
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => '123456',
        ];

        $this->postJson("api/login", $json)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('token'));
    }
    public function test_login_invalid_email()
    {
        User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $json = [
            'name' => 'Luis',
            'email' => 'luis@gmail.com',
            'password' => '123456',
        ];

        $this->postJson("api/login", $json)
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_login_invalid_password()
    {
        User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $json = [
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => '1234567',
        ];

        $this->postJson("api/login", $json)
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_logout()
    {
        User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $json = [
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => '123456',
        ];

        $response = $this->postJson("api/login", $json);
        $token = $response->baseResponse->original['token'];
        $header = [
            "Authorization" => "Bearer " . $token
        ];
        $this->postJson("api/logout", $json, $header)
            ->assertStatus(200)
            ->assertJson(['message' => 'Token Eliminado']);;
    }
}
