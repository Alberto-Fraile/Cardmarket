<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CardTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_card_ok()
    {
        $response = $this->putJson('/api/createCard', ['name' => 'carta', 'description' => 'carta buena','collection' => 'coleccion']);
        $response
            ->assertJson([
                'status' => 200,
            ]);
    }

    public function test_card_fail()
    {
        $response = $this->putJson('/api/createCard', ['name' => 'carta', 'description' => 'carta buena','collection' => '']);
        $response
            ->assertJson([
                'status' => 401,
                'msg' => 'La coleccion ingresada no existe',
            ]);
    }

    public function test_card_empty()
    {
        $response = $this->putJson('/api/createCard', ['name' => '', 'description' => '','collection' => '']);
        $response
            ->assertJson([
                'status' => 402,
            ]);
    }
}
