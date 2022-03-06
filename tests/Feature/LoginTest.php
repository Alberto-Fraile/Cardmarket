<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_ok()
    {
        $response = $this->postJson('/api/user/login', ['name' => 'alberto', 'password' => 'passWord7']);
        $response
            ->assertJson([
                'status' => 200,
            ]);
    }
    
    public function test_user_fail()
    {
        $response = $this->postJson('/api/user/login', ['name' => 'alberto', 'password' => 'aadd']);
        $response
            ->assertJson([
                'status' => 401,
                'msg' => 'Your password is incorrect',
            ]);
    }

    public function test_user_search()
    {
        $response = $this->postJson('/api/user/login', ['name' => 'alberta', 'password' => 'passWord7']);
        $response
            ->assertJson([
                'status' => 402,
                'msg' => 'Incorrect data',
            ]);
    }
}
