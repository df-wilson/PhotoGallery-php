<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeywordTest extends TestCase
{
    use RefreshDatabase;
    
    public function testGetAllKeywords()
    {
        logger("TodoItemTest::testGetAllKeywords - Enter");

        $this->seed();

        // Test unauthorized user. Should return all keywords.
        $response = $this->get('/api/keywords');

        $response->assertStatus(200)
            ->assertExactJson([
                'msg' => 'ok',
                'keywords' => [
                        [
                            "id" => "1",
                            "name" => "vancouver"
                        ],
                        [
                            "id" => "2",
                            "name" => "nature"
                        ]
                    ]
                ]);

        // Test authorized user.
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/api/keywords');

        $response->assertStatus(200)
            ->assertExactJson([
                'msg' => 'ok',
                'keywords' => [
                    [
                        "id" => "1",
                        "name" => "vancouver"
                    ],
                    [
                        "id" => "2",
                        "name" => "nature"
                    ]
                ]
            ]);

        logger("TodoItemTest::testGetAllKeywords - Leave");
    }

    public function testAddPhotoKeyword()
    {
        logger("TodoItemTest::testAddPhotoKeyword - Enter");

        $this->seed();

        // Test unauthorized
        $response = $this->json('POST', '/api/keywords/photo/1', ['keyword]' => 'test']);

        $response->assertStatus(401)
                 ->assertExactJson(
                     [
                        'msg' => 'not authorized'
                     ]
                 );

        // Test authorized user with no keyword supplied
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', '/api/keywords/photo/1', ['keyword' => '']);

        $response->assertStatus(422)
            ->assertExactJson(
                [
                    'msg' => 'keyword required'
                ]
            );

        $response = $this->get('/api/keywords');

        $response->assertStatus(200)
            ->assertExactJson([
                'msg' => 'ok',
                'keywords' => [
                    [
                        "id" => "1",
                        "name" => "vancouver"
                    ],
                    [
                        "id" => "2",
                        "name" => "nature"
                    ]
                ]
            ]);

        // Test authorized user with valid keyword
        $response = $this->actingAs($user)->json('POST', '/api/keywords/photo/1', ['keyword' => 'test']);

        $response->assertStatus(201)
            ->assertExactJson(
                [
                    'msg' => 'ok'
                ]
            );

        $response = $this->get('/api/keywords');

        $response->assertStatus(200)
            ->assertExactJson([
                'msg' => 'ok',
                'keywords' => [
                    [
                        "id" => "1",
                        "name" => "vancouver"
                    ],
                    [
                        "id" => "2",
                        "name" => "nature"
                    ],
                    [
                        "id" => "3",
                        "name" => "test"
                    ]
                ]
            ]);


        logger("TodoItemTest::testAddPhotoKeyword - Leave");
    }
}
