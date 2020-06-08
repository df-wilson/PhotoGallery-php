<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotoSearchTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchByKeywordPublicPhotos()
    {
        logger("testSearchByKeywordPublicPhotos - ENTER");

        $this->seed();

        // Test public users only get public photos
        $response = $this->json('GET',
            '/api/photos/search',
            [
                'keyword_id'       => '2',
                'text'             => '',
                'public_checkbox'  => '',
                'private_checkbox' => '',
                'from_date'        => '',
                'to_date'          => ''
            ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"]]
                ]
            );


        // Test user getting neither public nor own photos
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '1',
                    'text'             => '',
                    'public_checkbox'  => '',
                    'private_checkbox' => '',
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => []
                ]
            );

        // Test user getting public photos
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '1',
                    'text'             => '',
                    'public_checkbox'  => '1',
                    'private_checkbox' => '',
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                        ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"]]
                ]
            );

        logger("testSearchByKeywordPublicPhotos - LEAVE");
    }

    public function testSearchByKeywordOwnPhotos()
    {
        logger("testSearchByKeywordOwnPhotos - ENTER");

        $this->seed();


        // test user getting only own photos
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '1',
                    'text'             => '',
                    'public_checkbox'  => '',
                    'private_checkbox' => true,
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                        ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"]]
                ]
            );

        // test user searching only own photos when none for keyword
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '2',
                    'text'             => '',
                    'public_checkbox'  => '',
                    'private_checkbox' => true,
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => []
                ]
            );

        logger("testSearchByKeywordOwnPhotos - LEAVE");
    }

        public function testSearchByTextPublicPhotos()
        {
            logger("testSearchByTextPublicPhotos - ENTER");

            $this->seed();

            // Test public users only get public photos
            $response = $this->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '',
                    'text'             => 'Vancouver',
                    'public_checkbox'  => '',
                    'private_checkbox' => '',
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

            $response->assertStatus(200)
                ->assertExactJson(
                    [
                        'msg' => 'ok',
                        'photos' => [["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                            ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"]]
                    ]
                );

            // test user searching only own photos when none for text
            $user = User::find(1);
            $response = $this->actingAs($user)
                ->json('GET',
                    '/api/photos/search',
                    [
                        'keyword_id'       => '',
                        'text'             => 'picture',
                        'public_checkbox'  => true,
                        'private_checkbox' => false,
                        'from_date'        => '',
                        'to_date'          => ''
                    ]);

            $response->assertStatus(200)
                ->assertExactJson(
                    [
                        'msg' => 'ok',
                        'photos' => [["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"],
                                     ["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"]]
                    ]
                );

            logger("testSearchByTextPublicPhotos - LEAVE");
        }

        public function testSearchByTextOwnPhotos()
        {
            logger("testSearchByTextOwnPhotos - LEAVE");

            $this->seed();
            
            // test user searching only own photos when none for text
            $user = User::find(1);
            $response = $this->actingAs($user)
                ->json('GET',
                    '/api/photos/search',
                    [
                        'keyword_id'       => '',
                        'text'             => 'picture',
                        'public_checkbox'  => '',
                        'private_checkbox' => true,
                        'from_date'        => '',
                        'to_date'          => ''
                    ]);

            $response->assertStatus(200)
                ->assertExactJson(
                    [
                        'msg' => 'ok',
                        'photos' => [["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"]]
                    ]
                );

            logger("testSearchByTextOwnPhotos - LEAVE");
        }
    /*
           public function testSearchByDate()
           {

           }

           public function testSearchByKeywordAndText()
           {
               logger("testSearchByKeywordAndText - ENTER");

               $this->seed();

               // Search for private photos
               $photos = Photo::search(1, false, true, "", "", 1, "Vancouver");
               $this->assertEquals(1, count($photos));
               $this->assertEquals($photos[0]->id,"4");
               $this->assertEquals($photos[0]->name, "Giraffe");
               $this->assertEquals($photos[0]->thumbnail_filepath, "/storage/images/thumb_2012_04_09_012.jpg");

               $photos = Photo::search(2, false, true, "", "", 3, "Hippo");
               $this->assertEquals(1, count($photos));
               $this->assertEquals($photos[0]->id,"1");
               $this->assertEquals($photos[0]->name, "Hippo");
               $this->assertEquals($photos[0]->thumbnail_filepath, "/storage/images/thumb_2012_04_09_014.jpg");

               // Search for public photos

               // Search for public and private photos

               logger("testSearchByKeywordAndText - LEAVE");
           }

           public function testSearchByKeywordAndDate()
           {

           }

           public function testSearchByTextAndDate()
           {

           }

           public function testSearchByKeywordTextAndDate()
           {

           }

           */
}
